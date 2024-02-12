<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users_count = User::all()->count(); // Сначала получите $users_count
        $cities_count = City::count(); // Затем $cities_count

        return view('admin.home.index', compact('users_count', 'cities_count'));
    }

    public function storeindex()
    {
        $products_count = Product::all()->count();
        $orders_count = Order::all()->count();

        return view('store.dashboard', compact('products_count', 'orders_count'));

    }

}
