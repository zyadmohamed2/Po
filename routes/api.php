
<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\HomePageController;
use App\Http\Controllers\API\OrderController;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'API'], function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('cors');
    Route::post('/login', [AuthController::class, 'login'])->middleware('cors');
});
Route::group([
    'middleware' => ['api', 'cors'],
    'prefix' => "home",
    'controller' => HomePageController::class
], function () {
    Route::get('/categories', 'getAllCategories');
    Route::get('/banners', 'getAllBanners');
    Route::get('/products', 'getAllProducts');
    Route::get('/product/{id}', 'getProduct');
    Route::get('/popular-products', 'getPopularProducts');
    Route::get('/category/products/{cat_id}', 'getCategoryProducts');
    Route::post('/review', 'addReview');
});
Route::group(
    [
        'middleware' => ['api'],
        'prefix' => "cart",
        'controller' => CartController::class
    ],
    function () {
        Route::post('/product', 'addProductToCart')->middleware('cors');
        Route::patch('/product/{id}', 'updateProductInCart')->middleware('cors');
        Route::delete('/product/{id}', 'deleteProductInCart')->middleware('cors');
        Route::get('/products', 'getCartItems')->middleware('cors');
    }
);
Route::post('/order', [OrderController::class, 'createOrder'])->middleware(['api','jwt.verify', 'cors']);
Route::get('/shipping',function(){
return Shipping::where('status','active')->get();
})->middleware(['api','jwt.verify', 'cors']);
