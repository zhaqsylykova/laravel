<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function index()
    {

        $data['users'] = User::all();

        //join() or with
        return view('admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|phone|unique:users|max:255',
            //'city' => 'required|string|max:255', //
        ]);


        $user = new User;
        $user->name = $validatedData['name'];
        $user->phone = $validatedData['phone'];


        $user->save();
        $cityName = $request->input('city');

        if (!empty($cityName)) {
            $city = City::firstOrCreate(['city' => $cityName]);
            $user->city()->associate($city);
            $user->save();
        }
        //User::create($validatedData);
        return redirect()->route('user.index')->withSuccess('Пользователь успешно создан.');
    }


    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $cities = City::all(); // Получение всех городов
        return view('admin.user.edit', compact('user', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {   $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|phone|unique:users|max:255',
        //'city' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
    ]);

        //ADD LOGIC UPDATE PHONE, NAME
        $user -> name = $request -> name;
        $user -> phone = $request -> phone;
        $user -> city = $request -> city;

        $user -> save();

        $cityName = $request->city;

        $city = City::firstOrCreate(['city' => $cityName]);
        return redirect()->route('user.index')->withSuccess('Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //ADD DESTROYU
        $user->delete();
        return redirect()->back()->withSuccess('Deleted!');
    }

    function main(){

        return view('admin_layout');
    }

}
