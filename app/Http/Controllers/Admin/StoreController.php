<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function create(Request $request, Store $store)
    {
        $cities = City::all();
        if($request->photo){
            $store->photo = Storage::disk('public')->put("store/$store->id/photo" , $request->photo);
            $store->save();
        }
        return view('admin.stores.create', compact('store', 'cities'));
    }

    //public function index(Request $request)
    public function index()
    {
        $stores = Store::withCount('products')->get();
        return view('admin.stores.index', compact('stores'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'phone' => 'required|unique:stores|string',
            'city'=> 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $store = new Store();
        $store->name = $request->input('name');
        $store->description = $request->input('description');
        $store->phone = $request->input('phone');
        $store->city = $request->input('city');

        $store->save();

        if($request->photo) {
            $store->photo = Storage::disk('public')->put("store/$store->id/photo", $request->photo);
            $store->save();
        }
        return redirect()->route('store.index')->withSuccess('Магазин успешно создан.');


        /*$data['stores'] = Store::all();

        if ($request->isMethod('post')) {

            /*$validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'phone' => 'required|string',
                'photo' => 'required|string',
                'city'=> 'required|string',
                // Другие поля магазина
            ]);

            Store::create($validatedData);

            return redirect()->route('admin.stores.index')->withSuccess('Магазин успешно создан.');
        }

        return view('admin.stores.index', $data);*/
    }
    public function destroy(Store $store)
    {

        $store->delete();

        return redirect()->route('store.index')->with('success', 'Магазин успешно удален.');
    }
    public function edit(Store $store)
    {
        //dd($city);
        $cities = City::all();
        return view('admin.stores.edit', compact('store', 'cities'));

    }
    public function update(Request $request, Store $store)
    {
        $validatedData = $request->validate([

        //'city' => 'nullable|string|max:255',
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'phone' => 'required|unique:stores|string',
        //'photo' => 'required|string',
        'city' => 'required|string'
    ]);

        $store->update($validatedData);

        if($request->photo){
            $store->photo = Storage::disk('public')->put("store/$store->id/photo" , $request->photo);
            $store->save();
        //return redirect()->back()->withSuccess('Профиль успешно обновлен.');

        }
        return redirect()->route('store.index')->with('Информация о магазине успешно обновлена.');
    //return redirect()->route('admin.stores.index')->withSuccess('Магазин успешно создан.');
    //}

    //
}}
