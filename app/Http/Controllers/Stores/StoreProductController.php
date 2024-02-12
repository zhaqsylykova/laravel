<?php

namespace App\Http\Controllers\Stores;

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
use Illuminate\Validation\Rule;

class StoreProductController extends Controller
{
    public function index()
    {
        $store = auth('store')->user();

        $products = $store->products;

        return view('store.products.index', compact('products'));
    }

    public function dashboard()
    {
        $store = auth('store')->user();
        $products = $store->products;
        $totalQuantity = $products->sum('total_quantity');

        return view('store.dashboard', compact('products', 'totalQuantity'));
    }


    public function create()
    {
        $categories = Category::all();
        $stores = Store::all();
        $subcategories = Subcategory::all();
        return view('store.products.create', compact('categories', 'stores', 'subcategories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'total_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'store_id' => [
                'numeric',
                Rule::in([auth('store')->user()->id]),
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->total_quantity = $request->input('total_quantity');
        $product->category_id = $request->input('category_id');
        $product->subcategory_id = $request->input('subcategory_id');
        $product->store_id = auth('store')->user()->id;

        $product->save();

        if($request->photo) {
            $product->photo = Storage::disk('public')->put("product/$product->id/photo", $request->photo);
            $product->save();
        }
        return redirect()->route('store.product.index')->withSuccess('Product created successfully.');
    }




    public function edit(Product $product)
    {
        //dd($city);
        $categories = Category::all();
        $stores = Store::all();
        $subcategories = Subcategory::all();

        return view('store.products.edit', compact('categories', 'product', 'stores','subcategories',));

    }

    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',

        ];

        $messages = [
            'name.required' => 'Введите имя',
            'description.required' => 'Введите описание',
            'price.required' => 'Введите цену',
            'category_id.exists' => 'Категория не найдена',
            'category_id.required' => 'Введите данные для категории',
            'subcategory_id.exists' => 'Подкатегория не найдена',
            'subcategory_id.required' => 'Введите данные для подкатегории',

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

        return redirect()->route('store.product.index')->withSuccess('Информация о товаре успешно обновлена.');
    }

    public function destroy(Product $product)
    {

        $product->delete();

        return redirect()->route('store.product.index')->with('success', 'Товар успешно удален.');
    }


// Другие методы для редактирования и удаления товаров

}
