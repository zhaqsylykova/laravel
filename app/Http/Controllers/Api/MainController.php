<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Request;

class MainController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $products = Product::all()->map(function ($product) use ($user) {
            $inBasket = $user ? $user->basket->contains($product) : false;
            $inFavorites = $user ? $user->favorites->contains($product) : false;

            return [
                'product_name' => $product->name,
                'product_image' => $product->photo,
                'product_count' => $product->total_quantity,
                'product_current_count' => $product->available_quantity,
                'product_price' => $product->price,
                'in_basket' => $inBasket,
                'in_favorite' => $inFavorites,
            ];
        });

        return response()->json(['products' => $products]);
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
            'total_quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);

        }

        $product = new Product($request->all());
        $product->available_quantity = $request->input('total_quantity');
        $product->save();


        if ($request->hasFile('photo')) {
            $product->photo = Storage::disk('public')->put("product/{$product->id}/photo", $request->file('photo'));
            $product->save();
        }

        return response()->json(['message' => 'Product created successfully', 'product' => $product]);
    }

    public function purchaseProduct($productId, $quantity)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['error' => 'Товар не найден'], 404);
        }

        if ($product->available_quantity < $quantity) {
            return response()->json(['error' => 'Запрошенное количество недоступно'], 400);
        }

        $product->available_quantity -= $quantity;
        $product->save();

        return response()->json(['message' => 'Покупка прошла успешно', 'product' => $product]);
    }

    public function addToBasket(Request $request, Product $product)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->basket()->attach($product->id);

        return response()->json(['message' => 'Product added to basket']);
    }

    public function addToFavorites(Request $request, Product $product)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 400);
        }

        $user->favorites()->attach($product->id);

        return response()->json(['message' => 'Product added to favorites']);
    }

    public function getFavoriteProducts(Request $request)
    {
        $user = Auth::user();

      //  if (!$user) {
     //       return response()->json(['error' => 'Unauthorized'], 400);
     //   }

        $favoriteProducts = Product::join('favorites', 'products.id', 'favorites.product_id')
            ->where('favorites.user_id',  $user->id)
            ->select([
                'products.name as product_name',
                'products.photo as product_image',
                'products.total_quantity as product_count',
                'products.available_quantity as product_current_count',
                'products.price as product_price',
                \DB::raw('(CASE WHEN basket_user.product_id IS NOT NULL THEN "true" ELSE "false" END) as in_basket'),
                \DB::raw('(CASE WHEN favorites.product_id IS NOT NULL THEN "true" ELSE "false" END) as in_favorite'),
            ])
            ->leftJoin('basket_user', function ($join) use ($user) {
                $join->on('products.id', '=', 'basket_user.product_id')
                    ->where('basket_user.user_id', '=', $user->id);
            })
            ->get();

        return response()->json(['data' => $favoriteProducts]);
    }

    public function getBasketProducts(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $basketProducts = Product::join('basket_user', 'products.id', '=', 'basket_user.product_id')
            ->where('basket_user.user_id', '=', $user->id)
            ->select([
                'products.name as product_name',
                'products.photo as product_image',
                'products.total_quantity as product_count',
                'products.available_quantity as product_current_count',
                'products.price as product_price',
                \DB::raw('(CASE WHEN basket_user.product_id IS NOT NULL THEN "true" ELSE "false" END) as in_basket'),
                \DB::raw('(CASE WHEN favorites.product_id IS NOT NULL THEN "true" ELSE "false" END) as in_favorite'),
            ])
            ->leftJoin('favorites', function ($join) use ($user) {
                $join->on('products.id', '=', 'favorites.product_id')
                    ->where('favorites.user_id', '=', $user->id);
            })
            ->get();

        return response()->json(['data' => $basketProducts]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $products = Product::where('name', 'like', "%$search%")->get();

        return response()->json(['data' => $products]);
    }





































    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    public function edit(Product $product)
    {
        //dd($city);
        $categories = Category::all();
        $stores = Store::all();
        $subcategories = Subcategory::all();

        return view('admin.products.edit', compact('categories', 'product', 'stores','subcategories',));

    }

    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'store_id' => 'required|exists:stores,id',
        ];

        $messages = [
            'name.required' => 'Введите имя',
            'description.required' => 'Введите описание',
            'price.required' => 'Введите цену',
            'category_id.exists' => 'Категория не найдена',
            'category_id.required' => 'Введите данные для категории',
            'subcategory_id.exists' => 'Подкатегория не найдена',
            'subcategory_id.required' => 'Введите данные для подкатегории',
            'store_id.required' => 'Введите данные для магазина',
            'store_id.exists' => 'Магазин не найдена',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }


        $newRequest = array_filter($request->except(['photo']));
        $product->fill($newRequest);
        $product->save();

        if($request->photo){
            $product->photo = Storage::disk('public')->put("product/$product->id/photo" , $request->photo);
            $product->save();
        }

        return redirect()->route('product.index')->withSuccess('Информация о товаре успешно обновлена.');
    }

    public function destroy(Product $product)
    {

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Товар успешно удален.');
    }




}
