<?php

namespace App\Http\Controllers\User;

// app/Http/Controllers/Admin/OrderController.php

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\User;

class UserOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $orders = $user->orders()->with(['product'])->get();
            return view('user.orders.index', compact('orders'));
        }

        return redirect()->route('login')->with('error', 'Вы не авторизованы.');
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();

        return view('admin.orders.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {

        $product = Product::findOrFail($request->input('product'));
        $user = User::findOrFail($request->input('user'));
        $orderTotal = $product->price * $request->input('quantity');

        // Пример валидации:
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.orders.create')
                ->withErrors($validator)
                ->withInput();
        }

        Order::create([
            'user' => $user->name ,
            'quantity' => $request->input('quantity'),
            'status' => $request->input('status'),
            'product' => $product->name,
            'order_total' => $orderTotal]

        );


        return redirect()->route('order.index')->withSuccess('Заказ успешно создан.');
    }

    public function edit(Order $order)
    {

        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'order_time' => 'required|date',
            'order_number' => 'required|string',
            'product_name' => 'required|string',
            'quantity' => 'required|integer',
            'order_total' => 'required|numeric',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.orders.edit', $order->id)
                ->withErrors($validator)
                ->withInput();
        }

        $order->update($request->all());

        return redirect()->route('order.index')->withSuccess('Заказ успешно обновлен.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('order.index')->withSuccess('Заказ успешно удален.');
    }
}

