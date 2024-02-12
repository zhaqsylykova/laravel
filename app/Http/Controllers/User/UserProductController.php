<?php

namespace App\Http\Controllers\User;

use App\Models\Basket;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Request;

class UserProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('user.products.index', compact('products'));
    }

    public function buy($productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->available_quantity < 1) {
            return redirect()->back()->withErrors(['error' => 'Товар закончился']);
        }

        $order = new Order([
            'user_id' => auth()->user()->id,
            'store_id' => $product->store_id,
            'product' => $product->name,
            'order_time' => now(),
            'order_number' => uniqid(),
            'quantity' => 1,
            'order_total' => $product->price,
            'status' => 'В обработке',
        ]);

        $order->save();

        $product->available_quantity -= 1;
        $product->save();


        return redirect()->route('user.product.index')->with('success', 'Продукт успешно куплен!');
    }

    public function buyAll()
    {
        $user = auth()->user();

        $basketItems = Basket::where('user_id', $user->id)->with('product')->get();

        foreach ($basketItems as $item) {
            // Создать заказ
            $order = new Order([
                'user_id' => $user->id,
                'store_id' => $item->product->store_id,
                'product' => $item->product->name,
                'order_time' => now(),
                'order_number' => uniqid(),
                'quantity' => $item->quantity,
                'order_total' => $item->quantity * $item->product->price,
                'status' => 'В обработке',
            ]);

            $order->save();

            // Удалить товар из корзины
            $item->delete();
        }

        return redirect()->route('user.product.basket')->with('success', 'Все товары успешно куплены!');
    }


    public function addToFavorites($productId)
    {
        $user = auth()->user();

        if ($user->favorites->contains($productId)) {
            $user->favorites()->detach($productId);
            $message = 'Продукт успешно удален из избранного!';
        } else {
            $user->favorites()->attach($productId);
            $message = 'Продукт успешно добавлен в избранное!';
        }

        return redirect()->route('user.product.favorites')->with('success', $message);

    }
    public function favorites()
    {
        $favorites = auth()->user()->favorites;

        return view('user.favorites', compact('favorites'));
    }
    public function basket()
    {
        $basketItems = Basket::where('user_id', auth()->id())->with('product')->get();

        $totalAmount = $basketItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });


        return view('user.basket', compact('basketItems', 'totalAmount'));

    }

    public function addToBasket($productId, $quantity = 1)
    {
        $user = auth()->user();

        $products = Product::all();
        $quantity = max(1, (int)$quantity);

        $basketItem = Basket::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($basketItem) {
            $basketItem->increment('quantity', $quantity);
            $message = 'Продукт успешно добавлен в корзину!';
        } else {

            Basket::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
            $message = 'Продукт успешно добавлен в корзину!';
        }
        $basketItems = Basket::where('user_id', $user->id)->with('product')->get();
        $totalAmount = $basketItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return redirect()->route('user.product.basket')->with('success', $message);
    }


    public function update(Request $request, $productId)
    {
        $quantity = $request->input('quantity');

        if ($quantity <= 0) {
            return redirect()->back()->with('error', 'Некорректное количество товара.');
        }

        $basketItem = Basket::where('product_id', $productId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$basketItem) {
            return redirect()->back()->with('error', 'Продукт не найден в корзине.');
        }

        $basketItem->quantity = $quantity;

        $basketItem->save();
        return redirect()->back()->with('success', 'Количество обновлено');
    }

    public function removeFromBasket($productId)
    {
        $user = auth()->user();

        $basketItem = Basket::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($basketItem) {
            $basketItem->delete();
            return redirect()->route('user.product.basket')->with('success', 'Товар успешно удален из корзины!');
        } else {
            return redirect()->route('user.product.basket')->with('error', 'Товар не найден в корзине!');
        }
    }

    public function create()
    {
        $categories = Category::all();
        $stores = Store::all();
        $subcategories = Subcategory::all();
        return view('admin.products.create', compact('categories', 'stores', 'subcategories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'store_id' => 'required|exists:stores,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');
        $product->subcategory_id = $request->input('subcategory_id');
        $product->store_id = $request->input('store_id');

        $product->save();

        if($request->photo) {
            $product->photo = Storage::disk('public')->put("product/$product->id/photo", $request->photo);
            $product->save();
        }
        return redirect()->route('product.index')->withSuccess('Product created successfully.');
    }




    public function edit(Product $product)
    {
        //dd($city);
        $categories = Category::all();
        $stores = Store::all();
        $subcategories = Subcategory::all();

        return view('admin.products.edit', compact('categories', 'product', 'stores','subcategories',));

    }



    public function destroy(Product $product)
    {

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Товар успешно удален.');
    }

}
