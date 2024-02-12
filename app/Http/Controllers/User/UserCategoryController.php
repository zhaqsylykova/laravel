<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\Store;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserCategoryController extends Controller
{

    public function index(Request $request)
    {

        $data['categories'] = Category::all();
        if ($request->isMethod('post')) {
            // Валидация и сохранение магазина
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'icon' => 'required|string',
                // Другие поля магазина
            ]);

            Category::create($validatedData);
            // или
            // $store = new Store($validatedData);
            // $store->save();

            return redirect()->route('user.category.index')->withSuccess('Категория успешно создан.');
        }

        //join() or with
        return view('user.categories.index', $data);
    }
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',

        ];

        $messages = [
            'name.required' => 'Введите имя',

        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if($validator->fails()){
            return  back()->withErrors($validator->errors());
        }

        $category = new Category();
        $newRequest = array_filter($request->except(['icon']));
        $category->fill($newRequest);
        $category->save();

        if($request->icon){
            $category->icon = Storage::disk('public')->put("category/$category->id/icon" , $request->icon);
            $category->save();
        }
        //dd($validatedData);
        return redirect()->route('admin.category.index')->withSuccess('Категория успешно создана.');

    }

    public function edit(Category $category)
    {
        //dd($city);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required|string|max:255',
            //'icon' => 'string',

        ];
        $messages = [
            'name.required' => 'Введите имя',
        ];

        //    $city -> city = $request -> city;
        //    $city -> save();

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $newRequest = array_filter($request->except(['icon']));
        $category->fill($newRequest);
        $category->save();

        if($request->icon){
            $category->icon = Storage::disk('public')->put("category/$category->id/icon" , $request->icon);
            $category->save();
        }
        return redirect()->route('category.index')->withSuccess('Информация успешно обновлена.');
    }
    public function destroy(Category $category)
    {
        //ADD DESTROYU
        $category->delete();
        return redirect()->back()->withSuccess('Deleted!');

    }
}
