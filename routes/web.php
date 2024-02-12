<?php

use App\Http\Controllers\Auth\StoreRegisterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\User\UserUserController;
use App\Http\Controllers\User\UserBannerController;
use App\Http\Controllers\User\UserCategoryController;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\UserProductController;
use App\Http\Controllers\User\UserStoreController;
use App\Http\Controllers\User\UserSubcategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\StoreLoginController;
use App\Http\Controllers\Stores\StoreBannerController;
use App\Http\Controllers\Stores\StoreCategoryController;
use App\Http\Controllers\Stores\StoreSubcategoryController;
use App\Http\Controllers\Stores\StoreProductController;
use App\Http\Controllers\TwilioController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Stores\StoreOrderController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Models\Category;
use App\Models\Subcategory;
use Symfony\Component\Console\Input\Input;

use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');
Route::get('/login', [LoginController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/ajax-subcat', function (Request $request) {
    $cat_id = $request->input('cat_id');
    $subcategories = Subcategory::where('category_id', '=', $cat_id)->get();
    return response()->json($subcategories);
});

Route::get('/messages', [ChatController:: class, 'fetchMessages']);
Route::get('/chat/{orderId}', [ChatController:: class, 'index'])->name('chat');
Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send.message');

Route::resource('users', UserUserController::class);
Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('dashboard', [UserUserController::class, 'dashboard'])->name('user.dashboard');

    Route::prefix('banner')->name('user.banner.')->group(function () {
        Route::get('/', [UserBannerController::class, 'index'])->name('index');
        //осыны косу керек
    });
    // routes/web.php
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send-message');


    Route::prefix('product')->name('user.product.')->group(function () {
        Route::get('/', [UserProductController::class, 'index'])->name('index');
        Route::get('/{product}/buy', [UserProductController::class,'buy'])->name('buy');
        Route::post('/product/{product}/add-to-favorites', [UserProductController::class,'addToFavorites'])->name('add.to.favorites');
        Route::get('/favorites', [UserProductController::class, 'favorites'])->name('favorites');
        //корзина косу керек
        Route::get('/basket', [UserProductController::class, 'basket'])->name('basket');
        Route::post('/add-to-basket/{product}', [UserProductController::class, 'addToBasket'])->name('basket.add');
        Route::patch('/update/{product}', [UserProductController::class, 'update'])->name('update');
        Route::delete('/basket/remove/{product}', [UserProductController::class, 'removeFromBasket'])->name('basket.remove');
        Route::post('/basket/buy-all', [UserProductController::class, 'buyAll'])->name('basket.buy-all');

    });


    Route::prefix('order')->name('user.order.')->group(function () {
        Route::get('/', [UserOrderController::class, 'index'])->name('index');
    });
});



Route::get('/store/login', [StoreLoginController::class, 'showLoginForm'])->name('store.login');
Route::post('/store/login', [StoreLoginController::class, 'login']);
Route::get('/store/register', [StoreRegisterController::class, 'create'])->name('store.register');
Route::post('/store/register', [StoreRegisterController::class, 'store']);
Route::post('/store/logout', [StoreLoginController::class, 'logout'])->name('store.logout');
Route::middleware('auth:store')->prefix('store')->group(function () {
    Route::get('dashboard', [StoreLoginController::class, 'dashboard'])->name('store.dashboard');
    Route::get('/edit', [StoreLoginController::class, 'edit'])->name('store.editor');
    Route::put('/update', [StoreLoginController::class, 'update'])->name('store.updater');


    Route::prefix('order')->name('store.order.')->group(function () {
        Route::get('{order}/edit', [StoreOrderController::class, 'edit'])->name('edit');
        Route::put('{order}/update', [StoreOrderController::class, 'update'])->name('update');
        Route::get('create', [StoreOrderController::class, 'create'])->name('create');
        Route::post('store', [StoreOrderController::class, 'store'])->name('store');
        Route::get('/', [StoreOrderController::class, 'index'])->name('index');
        Route::delete('{order}/destroy', [StoreOrderController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('product')->name('store.product.')->group(function () {
        Route::get('{product}/edit', [StoreProductController::class, 'edit'])->name('edit');
        Route::put('{product}/update', [StoreProductController::class, 'update'])->name('update');
        Route::get('create', [StoreProductController::class, 'create'])->name('create');
        Route::post('store', [StoreProductController::class, 'store'])->name('store');
        Route::get('/', [StoreProductController::class, 'index'])->name('index');
        Route::delete('{product}/destroy', [StoreProductController::class, 'destroy'])->name('destroy');
    });
    });



Route::resource('users', UserController::class);
Route::resource('city', CityController::class);
Route::resource('store', StoreController::class);
Route::resource('category', CategoryController::class);
Route::resource('product', ProductController::class);
Route::resource('banner', BannerController::class);
Route::resource('order', OrderController::class);
Route::resource('subcategory', SubcategoryController::class);

Route::middleware(['is_admin'])->prefix('admin_panel')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home'); // /admin

    Route::prefix('city')->name('city.')->group(function () {
        Route::get('{city}/edit', [CityController::class, 'edit'])->name('edit');
        Route::put('{city}/update', [CityController::class, 'update'])->name('update');
        Route::get('create', [CityController::class, 'create'])->name('create');
        Route::post('store', [CityController::class, 'store'])->name('store');
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::delete('{city}/destroy', [CityController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('{user}/update', [UserController::class, 'update'])->name('update');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::delete('{user}/destroy', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('banner')->name('banner.')->group(function () {
        Route::get('{banner}/edit', [BannerController::class, 'edit'])->name('edit');
        Route::put('{banner}/update', [BannerController::class, 'update'])->name('update');
        Route::get('create', [BannerController::class, 'create'])->name('create');
        Route::post('store', [BannerController::class, 'store'])->name('store');
        Route::get('/', [BannerController::class, 'index'])->name('index');
        Route::delete('{banner}/destroy', [BannerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('store')->name('store.')->group(function () {
        Route::get('{store}/edit', [StoreController::class, 'edit'])->name('edit');
        Route::put('{store}/update', [StoreController::class, 'update'])->name('update');
        Route::get('create', [StoreController::class, 'create'])->name('create');
        Route::post('store', [StoreController::class, 'store'])->name('store');
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::delete('{store}/destroy', [StoreController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('category')->name('category.')->group(function () {
        Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('{category}/update', [CategoryController::class, 'update'])->name('update');
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::delete('{category}/destroy', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('subcategory')->name('subcategory.')->group(function () {
        Route::get('{subcategory}/edit', [SubcategoryController::class, 'edit'])->name('edit');
        Route::put('{subcategory}/update', [SubcategoryController::class, 'update'])->name('update');
        Route::get('create', [SubcategoryController::class, 'create'])->name('create');
        Route::post('store', [SubcategoryController::class, 'store'])->name('store');
        Route::get('/', [SubcategoryController::class, 'index'])->name('index');
        Route::delete('{subcategory}/destroy', [SubcategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('product')->name('product.')->group(function () {
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('{product}/update', [ProductController::class, 'update'])->name('update');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::delete('{product}/destroy', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('order')->name('order.')->group(function () {
        Route::get('{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('{order}/update', [OrderController::class, 'update'])->name('update');
        Route::get('create', [OrderController::class, 'create'])->name('create');
        Route::post('store', [OrderController::class, 'store'])->name('store');
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::delete('{order}/destroy', [OrderController::class, 'destroy'])->name('destroy');
    });

    /*Route::middleware('guest')->group(function () {
        Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
        Route::get('/reset-password', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
    });*/
});

