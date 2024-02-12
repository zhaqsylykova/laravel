<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserBannerController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        $banners = Banner::all();
        return view('user.banners.index', compact('stores','banners'));
    }

    public function create()
    {
        $stores = Store::all();
        // Отображение формы создания баннера
        return view('admin.banners.create', compact('stores'));
    }

    public function store(Request $request)
    {
        // Валидация и сохранение баннера
        $rules = [
            'store' => 'required|exists:stores,id',
            'caption' => 'required|string',


        ];

        $messages = [
            'name.required' => 'Введите имя',
            'caption.required' => 'Введите описание',
            'store.exists' => 'Магазин не найден',

        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if($validator->fails()){
            return  back()->withErrors($validator->errors());
        }

        $banner = new Banner();
        $newRequest = array_filter($request->except(['photo']));
        $banner->fill($newRequest);
        $banner->save();
        $store = Store::findOrFail($request->input('store')); // Находим магазин по ID
        $banner->store()->associate($store);

        // Загрузка и сохранение фото баннера
        if($request->photo){

            $banner->photo = Storage::disk('public')->put("banner/$banner->id/photo" , $request->photo);
            $banner->save();
        }



        return redirect()->route('banner.index')->withSuccess('Баннер успешно создан.');
    }
    public function edit(Banner $banner)
    {
        //dd($city);
        $stores = Store::all();

        return view('admin.banners.edit', compact('stores',  'banner'));

    }

    public function update(Request $request, Banner $banner)
    {
        $rules = [
            'store' => 'required|exists:stores,id',
            'caption' => 'required|string',
        ];

        $messages = [
            'name.required' => 'Введите имя',
            'caption.required' => 'Введите описание',
            'store.exists' => 'Магазин не найден',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $newRequest = array_filter($request->except(['photo']));
        $banner->fill($newRequest);
        $banner->save();

        if($request->photo){
            $banner->photo = Storage::disk('public')->put("banner/$banner->id/photo" , $request->photo);
            $banner->save();
        }

        return redirect()->route('banner.index')->withSuccess('Информация о баннере успешно обновлена.');
    }

    public function destroy(Banner $banner)
    {

        $banner->delete();

        return redirect()->route('banner.index')->with('success', 'Баннер успешно удален.');
    }

    // Добавьте методы для редактирования, обновления и удаления баннеров по аналогии с предыдущими контроллерами.
}
