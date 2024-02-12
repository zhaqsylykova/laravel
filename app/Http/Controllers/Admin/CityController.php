<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::all();

        $cityUsersCount = [];

        foreach ($cities as $city) {
            $cityUsersCount[$city->id] = User::where('city', $city->city)->count();
        }

        return view('admin.cities.index', compact('cities', 'cityUsersCount'));
    }
    public function create()
    {
        return view('admin.cities.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'city' => 'required|string|max:255', // Поле "city" в правилах валидации
        ]);
        $existingCity = City::where('city', $validatedData['city'])->first();

        if ($existingCity) {
            return redirect()->route('city.index')->withErrors('Город с таким именем уже существует.');
        }

        City::create($validatedData);

        return redirect()->route('city.index')->withSuccess('Город успешно добавлен.');
    }


    public function edit(City $city)
    {
        //dd($city);
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validatedData = $request->validate([
            'city' => 'required|string|max:255',

        ]);
        //    $city -> city = $request -> city;
        //    $city -> save();

        $city->update($validatedData);
        //return redirect()->back()->withSuccess('Updated!');
        return redirect()->route('city.index')->withSuccess('Информация о городе успешно обновлена.');
    }

    public function destroy(City $city)
    {
        //$city->delete();

        //return redirect()->route('admin.cities.index')->withSuccess('Город успешно удален.');

        $usersWithCity = $city->users;

        if ($usersWithCity->isEmpty()) {
            $city->delete();
            return redirect()->route('city.index')->withSuccess('Город успешно удален.');
        } else {
            return redirect()->route('city.index')->withWarning('Невозможно удалить город, так как он привязан к пользователям.');
        }
    }


    private function updateCityData()
    {
        $cities = City::all();

        foreach ($cities as $city) {
            $userCount = $city->users()->count();
            if ($userCount > 0) {
                $cityUsersCount[$city->id] = $userCount;
            // Обновите значение users_count в записи города
            $city->update(['users_count' => $userCount]);
        }
        }
        $cities = $cities->filter(function ($city) use ($cityUsersCount) {
            return array_key_exists($city->id, $cityUsersCount);
        });

        return view('admin.cities.index', compact('cities', 'cityUsersCount'));
}}
