<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Order;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log;


class StoreLoginController extends Controller
{

    public function dashboard()
    {
        $store = auth('store')->user();
        $products = $store->products;
        $totalQuantity = $products->sum('total_quantity');
        $availableQuantity = $products->sum('available_quantity');
        $totalOrderQuantity = Order::where('store_id', $store->id)->count();
        $productsSold = $store->orders->sum('quantity');
        $orders = $store->orders;
        $totalPrice = $orders->sum('order_total');

        return view('store.dashboard', compact('totalQuantity', 'totalOrderQuantity', 'totalPrice', 'availableQuantity', 'productsSold'));
    }



    public function showLoginForm()
    {
        return view('store.store-login');
    }




    public function login(Request $request)
    {

        $credentials = $request->validate([
            'phone' => 'required|phone',
            'password' => ['required']
        ]);

        $phone = $request->get('phone');
        $password = $request->get('password');

        if(Auth::guard('store')->validate(['phone' => $phone, 'password' => $password])){
            $store = Store::where('phone', $phone)->first();
       //     Auth::guard('store')->setUser($store);
            Auth::guard('store')->loginUsingId($store->id) ;
            $request->session()->regenerateToken();

            $products = $store->products;
            $orders = $store->orders;

            $data = [
                'totalOrderQuantity' => $store->orders->count(),
                'productsSold' => $store->orders->sum('quantity'),
                'totalQuantity'=> $products->sum('total_quantity'),
                'totalPrice' => $orders->sum('order_total'),
                'availableQuantity'=> $products->sum('available_quantity'),
            ];

            return view('store.dashboard', $data); //, $data
        } else {
            return back()->withInput()->withErrors(['password' => 'Invalid credentials']);
        }

    }

    public function edit()
    {
        $store = auth('store')->user();
        $cities = City::all();
        return view('store.editor', compact('store','cities'));
    }

    public function update(Request $request, Store $store)
    {
        $store = auth('store')->user();
        $validatedData = $request->validate([

            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string'
        ]);

        $store->update($validatedData);

        if ($request->photo) {
            $store->photo = Storage::disk('public')->put("store/$store->id/photo", $request->photo);
            $store->save();

        }
        return redirect()->route('store.editor')->with('Информация о магазине успешно обновлена.');
    }
    public function logout()
    {
        Auth::guard('store')->logout();

        return redirect()->route('store.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /*use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /*public function create()
    {
        return view('auth.store-login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|phone',
            'password' => ['required', 'string']
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'phone' => trans('auth.failed')
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
    /*protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token]);
        }

        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }*/
}
