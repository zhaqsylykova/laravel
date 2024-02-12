<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\City;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ListController extends Controller
{
    public function citiesList()
    {
        $cities = City::all();
        return response()->json($cities);
    }
    public function usersList()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function categoriesList()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function subcategoriesList()
    {
        $subcategories = Subcategory::all();
        return response()->json($subcategories);
    }

    public function ordersList()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    public function productsList()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function storesList()
    {
        $stores = Store::all();
        return response()->json($stores);
    }


}
