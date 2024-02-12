<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OrderResource;
use App\Http\Resources\Api\OrderStoreResource;
use App\Http\Resources\Api\StoreResource;
use App\Models\Order;
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
use App\Models\Store;




class StoreController extends Controller
{
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
            'phone' => 'required|phone|unique:stores',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $store = Store::create($input);

        $confirmationCode = mt_rand(1000, 9999);
        $store->confirmation_code = $confirmationCode;
        $store->save();

        $twilioPhoneNumber = getenv('TWILIO_PHONE_NUMBER');
        $twilioAuthToken = getenv('TWILIO_AUTH_TOKEN');
        $sid = getenv("TWILIO_ACCOUNT_SID");

        $twilio = new Client($sid, $twilioAuthToken);

        $twilio->messages->create(
            '+' .$store->phone, // Номер телефона пользователя
            [
                "body" => "Your confirmation code is: " . $confirmationCode,
                "from" => $twilioPhoneNumber,
            ]
        );

        $responseMessage = 'Вы прошли регистрацию успешно.';

        $success['token'] =  $store->createToken('API_STORES')->plainTextToken;
        $success['store'] =  $store;

        $success['message'] = $responseMessage;
        return response()->json(['success'=>$success], $this-> successStatus);

    }

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

        $store = Store:: where('phone', $request['phone'])->first();

        if ( !Hash::check($request['password'], $store->password)) {
            $store->increment('login_attempts' ,1);
            $store->save();

            if ($store->login_attempts >= 3) {
                return response()->json(['message' => 'Превышено количество попыток. Если забыли пароль, попробуйте сбросить пароль.'], 400);
            }
            return response()->json(['message' => "Неверный пароль"], 400);
        }

        $store->update(['login_attempts' => 0]);

        $token = $store->createToken('API_STORES')->plainTextToken;

        return response()->json([
            'data' => [
                'store' => $store,
                'access_token' => $token,
                ],
            ]);
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

        $store = Store::where('phone', $request->input('phone'))->first();

        if (!$store) {
            return response()->json(['error' => 'store not found'], 400);
        }

        if ($store->confirmation_code === $request->input('confirmation_code')) {
            $store->markPhoneAsVerified();
            return response()->json(['success' => 'Phone number confirmed'], 200);
        }

        return response()->json([
            'error' => 'Invalid confirmation code'], 400);
    }

    public function index()
    {
        return StoreResource::collection(Store::all());
    }

    public function show($id)
    {
        return new StoreResource(Store::find($id));
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
        $store = Store::create($input);

        return response()->json(
            $store, 200);
    }

    //public function update(Request $request)

    public function update(Request $request, $id)
    {
        $store = Store::find($id); //findOrFail

        if (!$store) {
            return response()->json(['error' => 'store not found'], 404);
        }

        if ($request->name) {
            $store->name = $request->name;
        }

        if ($request->phone) {
            $store->phone = $request->phone;
        }

        if ($request->city) {
            $store->city = $request->city; //
        }

        $store->save();

        return response()->json($store, 200);
    }

    public function delete($id)
    {
        Store::whereId($id)->delete();
        return response()->json(
            'успешно удалено', 200
        );
    }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|phone',
        ]);

        $store = Store::where('phone', $request->input('phone'))->first();
        $passwordResetCode = mt_rand(1000, 9999);

        $twilioPhoneNumber = getenv('TWILIO_PHONE_NUMBER');
        $twilioAuthToken = getenv('TWILIO_AUTH_TOKEN');
        $sid = getenv("TWILIO_ACCOUNT_SID");

        $twilio = new Client($sid, $twilioAuthToken);

        $twilio->messages->create(
            '+' . $store->phone,
            [
                "body" => "Your reset code is: " . $passwordResetCode,
                "from" => $twilioPhoneNumber,
            ]
        );

        // Сохранение кода в базе данных
        $store->update(['password_reset_code' => $passwordResetCode]);
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

        $store = Store::where('phone', $request->input('phone'))->first();
        if (!$store) {
            return response()->json(['message' => 'Данный номер не зарегистрирован в нашей системе'], 400);
        }
        $passwordResetCode = $request->input('password_reset_code');
        if ($store->password_reset_code !== $passwordResetCode) {
            return response()->json(['message' => 'Неверный код'], 400);
        }
        $store->update([
            'password' => bcrypt($request->input('password')),
            'password_reset_code' => null,
        ]);
        return response()->json(['message' => 'Password reset successfully']);
    }

    public function orders()
    {
        $storeId = auth('store')->id();

        if ($storeId) {
            // Загружаем заказы для данного продавца
            $orders = Order::where('store_id', $storeId)->with(['user', 'product'])->get();

            // Передаем заказы в представление
            return view('store.order.index', ['orders' => $orders]);
        }

        // Если не удалось получить ID, возвращаем ошибку
        return redirect()->route('store.login')->with('error', 'Вы не авторизованы как продавец.');
    }







}
