<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::all();
        return view('admin.subcategories.index', compact('subcategories'));
        // Вывод списка подкатегорий
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.subcategories.create', compact('categories', 'subcategories'));
        // Отображение формы создания подкатегории
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',

        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $subcategory = new Subcategory();
        $subcategory->name = $request->input('name');
        $subcategory->category_id = $request->input('category_id');

        $subcategory->save();


        //return redirect()->route('admin.subcategories.index')->withSuccess('Subcategory created successfully.');
        //Subcategory::create([

        //'name' => $request->input('name') ,
        //'category_id' => $request->input('category_id')
        //'category' => $request->input('category'),
        //]



        return redirect()->route('subcategory.index')->with('Подкатегория успешно создана.');

    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();

        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
        // Отображение формы редактирования подкатегории
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',


        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.subcategories.edit', $subcategory->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Обновление заказа
        $subcategory->update($request->all());

        return redirect()->route('subcategory.index')->withSuccess('Подкатегория успешно обновлена.');
        // Логика обновления подкатегории
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('subcategory.index')->withSuccess('Подкатегория успешно удалена.');
        // Логика удаления подкатегории
    }


}

