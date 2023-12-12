<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
  Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware(['auth:sanctum' ])->group(function () {
  Route::get('/isAuthenticated', function () {
    return response()->json([
      'message' => 'Authenticated.'
    ]);
  })->middleware('apiIsAdmin');

  // category routes
  Route::apiResource('/categories', CategoryController::class);

  // product routes
  Route::apiResource('/products', ProductController::class);
});

// product
Route::get('/get-product-by-category/{slug}', [ProductController::class, 'getProductByCategory']);
Route::get('/enable-categories', [CategoryController::class, 'getEnableCategories']);
Route::get('/random_products', [ProductController::class, 'getRandom']);

// cart
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::get('/cart-items' , [CartController::class , 'cartItems']);
Route::patch('/cart-items-updateQty/{id}' , [CartController::class , 'updateCartProductQty']);
Route::delete('/cart-item-delete/{id}' , [CartController::class , 'deleteCartProduct']);
Route::delete('/cart-delete' ,[CartController::class , 'deleteCart']);

// order
Route::get('/orders' , [\App\Http\Controllers\OrderController::class , 'getOrders'])->middleware(['auth:sanctum' , 'apiIsAdmin']);

// stripe
Route::post('/checkout' , [StripeController::class , 'checkout']);
Route::post('/check-payment-success' , [StripeController::class , 'checkSuccessPayment']);
