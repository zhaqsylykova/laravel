<?php

use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\TwilioController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::get('/', 'index');
    Route::get('{id}', 'show');
    Route::post('/verify','verify');
    Route::post('store',  'store');
    Route::post('update/{id}', 'update');
    Route::post('delete/{id}', 'delete');
    Route::post('password/forgot','forgotPassword');
    Route::post('password/reset', 'resetPassword');
    Route::get('orders', 'orders')->middleware('auth:sanctum');
});

Route::controller(MainController::class)->prefix('product')->group(function () {
    Route::get('/', 'index');
    Route::post('/store',  'store');
    Route::post('/purchase-product/{productId}/{quantity}', 'purchaseProduct');
    Route::post('/add-to-basket/{product}','addToBasket');
    Route::post('/add-to-favorites/{product}', 'addToFavorites');
    Route::get('/favorite', 'getFavoriteProducts');
    Route::get('/basket', 'getBasketProducts');
    Route::get('/search', 'search');
});

Route::controller(StoreController::class)->prefix('store')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('/', 'index');
    Route::get('{id}', 'show');
    Route::post('verify','verify');
    Route::post('store',  'store');
    Route::post('update/{id}', 'update');
    Route::post('delete/{id}', 'delete');
    Route::post('password/forgot','forgotPassword');
    Route::post('password/reset', 'resetPassword');
    Route::get('orders', 'orders');

});


//Route::controller(MainController::class)->prefix('main')->group(function () {
    //Route::post('gift/product', 'gift/product');



    //ToDo
    // return [
    // 'prouduct_name' = 'qwe',
    // 'product_image' = 'storage/',
    // 'product_count' = 123,
    // 'product_current_count = 10,
    // 'product_price' = 100,
    // 'in_basket' =  true / false,
    // 'in_favorite' = true/ false,
    //
    //\]


    //Route::post('user/favorite', 'userFavorite');

        //ToDo
        // Юзер коскан товарлар кайту керек
        // return [
        // 'prouduct_name' = 'qwe',
        // 'product_image' = 'storage/',
        // 'product_count' = 123,
        // 'product_current_count = 10,
        // 'product_price' = 100,
        // 'product_compound' = 10,
        // 'in_basket' =  true / false,
        // 'in_favorite' = true/ false,
        //
        //\]



    //Route::post('user/basket', 'userBasket');

    //ToDo
    // Юзер коскан товарлар кайту керек
    // return [
    // 'prouduct_name' = 'qwe',
    // 'product_image' = 'storage/',
    // 'basket_count' = 123,
    // 'product_size' = 'xl',
    // 'product_bonus = 12,
    // 'product_price' = 100,
    // 'product_compound' = 10,
    //
    //\]

//});



//Route::get('/send-sms', [TwilioController::class, 'sendVerificationCode']);


/*Route::controller(MainController::class)->prefix('main')->group(function (){
    Route::get('cities', 'citiesList');
    Route::get('users', 'usersList');
    //  Route::get('stores', 'storesList');
    //   Route::get('orders', 'ordersList');
    //  Route::get('products', 'productsList');
    Route::get('categories', 'categoriesList');
    Route::get('subcategories', 'subcategoriesList');
});*/


Route::middleware('auth:sanctum')->get('orders', [UserController::class, 'orders']);



