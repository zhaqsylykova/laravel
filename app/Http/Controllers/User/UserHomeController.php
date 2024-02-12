<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\City;

class UserHomeController extends Controller
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


     /* Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)->get();
        $favoritesCount = $user->favorites()->count();
        $totalSpent = $orders->sum('order_total');
        $ordersCount = $orders->count();

        return view('user.dashboard', compact('favoritesCount', 'totalSpent', 'ordersCount'));
    }

}
