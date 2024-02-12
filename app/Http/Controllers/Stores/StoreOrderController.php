<?php

namespace App\Http\Controllers\Stores;

// app/Http/Controllers/Admin/OrderController.php

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\User;



class StoreOrderController extends Controller
{
    public function index()
    {
        $store = auth('store')->user();

        if ($store) {
            $orders = $store->orders()->with(['user', 'product'])->get();

            return view('store.orders.index', ['orders' => $orders]);
        }

        return redirect()->route('store.login')->with('error', 'Вы не авторизованы как продавец.');
    }
    public function create()
    {
        $users = User::all();
        $products = Product::all();

        return view('store.orders.create', compact('users', 'products'));
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
            return redirect()->route('store.orders.create')
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


        return redirect()->route('store.order.index')->withSuccess('Заказ успешно создан.');
    }

    public function edit(Order $order)
    {
        $users = User::all();
        $product = Product::find($order->product);
        return view('store.orders.edit', compact('order', 'product', 'users') );
    }

    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('store.orders.edit', $order->id)
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

