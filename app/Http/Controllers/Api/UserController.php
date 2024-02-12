<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OrderResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Events\SendSmsCode;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Aloha\Twilio\Twilio;
use Twilio\Rest\Client;



class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|phone', //|exists.users
            'password' => 'required',

        ], [
            'required' => 'Необходимо заполнить все поля',
            'phone.required' => 'Поле "phone" обязательно для заполнения',
            'phone.phone' => 'Неверный формат номера телефона',
            'phone.exists' => 'Данный номер не зарегистрирован в нашей системе',
            'password.required' => 'Поле "password" обязательно для заполнения',
        ]);

        $user = User:: where('phone', $request['phone'])->first();

        if ( !Hash::check($request['password'], $user->password)) {
            $user->increment('login_attempts' ,1);
            $user->save();

            if ($user->login_attempts >= 3) {
                return response()->json(['message' => 'Превышено количество попыток. Если забыли пароль, попробуйте сбросить пароль.'], 400);
            }
            return response()->json(['message' => "Неверный пароль"], 400);
        }

        $user->update(['login_attempts' => 0]);

        $token = $user->createToken('API_USERS')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'access_token' => $token,
                ],
            ]);
    }

    private $successStatus = 200;
    public function register(Request $request)
    {

        $messages = [
            'required' => 'Необходимо заполнить все поля',
            'phone' => 'Неверный формат номера телефона',
            'same' => 'Пароли не совпадают',
            'unique' => 'Данный номер уже зарегистрирован в системе',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|phone|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $confirmationCode = mt_rand(1000, 9999);
        $user->confirmation_code = $confirmationCode;
        $user->save();

        $twilioPhoneNumber = getenv('TWILIO_PHONE_NUMBER');
        $twilioAuthToken = getenv('TWILIO_AUTH_TOKEN');
        $sid = getenv("TWILIO_ACCOUNT_SID");

        $twilio = new Client($sid, $twilioAuthToken);

        $twilio->messages->create(
            '+' .$user->phone, // Номер телефона пользователя
            [
                "body" => "Your confirmation code is: " . $confirmationCode,
                "from" => $twilioPhoneNumber, // Ваш Twilio номер
            ]
        );

        $responseMessage = 'Вы прошли регистрацию успешно.';

        $success['token'] =  $user->createToken('API_USERS')->plainTextToken;
        $success['user'] =  $user;

        $success['message'] = $responseMessage;
        return response()->json(['success'=>$success], $this-> successStatus);

    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'confirmation_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::where('phone', $request->input('phone'))->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 400);
        }

        if ($user->confirmation_code === $request->input('confirmation_code')) {
            $user->markPhoneAsVerified();
            return response()->json(['success' => 'Phone number confirmed'], 200);
        }

        return response()->json([
            'error' => 'Invalid confirmation code'], 400);
    }

    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    public function store(Request $request)
    {
        /*$created_user = User::create($request->all());
        return new UserResource($created_user);*/
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|phone',
            'city' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all(); // validate fill
        $user = User::create($input);

        return response()->json(
            $user, 200);
    }

    //public function update(Request $request)

    public function update(Request $request, $id)
    {
        $user = User::find($id); //findOrFail

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($request->name) {
            $user->name = $request->name;
        }

        if ($request->phone) {
            $user->phone = $request->phone;
        }

        if ($request->city) {
            $user->city = $request->city; //
        }

        // add avatar

        $user->save();

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        User::whereId($id)->delete();
        return response()->json(
            'успешно удалено', 200
        );
    }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|phone',
        ]);

        // Генерация и отправка SMS-кода
        $user = User::where('phone', $request->input('phone'))->first();
        $passwordResetCode = mt_rand(1000, 9999);

        $twilioPhoneNumber = getenv('TWILIO_PHONE_NUMBER');
        $twilioAuthToken = getenv('TWILIO_AUTH_TOKEN');
        $sid = getenv("TWILIO_ACCOUNT_SID");

        $twilio = new Client($sid, $twilioAuthToken);

        $twilio->messages->create(
            '+' . $user->phone,
            [
                "body" => "Your reset code is: " . $passwordResetCode,
                "from" => $twilioPhoneNumber,
            ]
        );

        // Сохранение кода в базе данных
        $user->update(['password_reset_code' => $passwordResetCode]);
        return response()->json(['message' => 'SMS code sent successfully']);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone',
            'password_reset_code' => 'required',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|same:password',
        ], [
            'required' => 'Необходимо заполнить все поля',
            'min' => 'Пароль должен содержать не менее :min символов',
            'same' => 'Пароли не совпадают',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::where('phone', $request->input('phone'))->first();
        if (!$user) {
            return response()->json(['message' => 'Данный номер не зарегистрирован в нашей системе'], 400);
        }
        $passwordResetCode = $request->input('password_reset_code');
        if ($user->password_reset_code !== $passwordResetCode) {
            return response()->json(['message' => 'Неверный код'], 400);
        }
        $user->update([
            'password' => bcrypt($request->input('password')),
            'password_reset_code' => null,
        ]);
        return response()->json(['message' => 'Password reset successfully']);
    }

    public function orders()
    {
        $userId = auth()->id();

        if ($userId) {
            $orders = Order::where('user_id', $userId)->get();

            return response()->json(['orders' => OrderResource::collection($orders)]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }



}
