<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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


Route::middleware(['auth:sanctum', 'apiIsAdmin'])->group(function () {
  Route::get('/isAuthenticated', function () {
    return response()->json([
      'message' => 'Authenticated.'
    ]);
  });

  // category routes
  Route::apiResource('/categories', CategoryController::class);
  Route::get('/enable-categories', [CategoryController::class, 'getEnableCategories']);

  // product routes
  Route::apiResource('/products', ProductController::class);
});

// product
Route::get('/get-product-by-category/{slug}', [ProductController::class, 'getProductByCategory']);

// cart
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::get('/cart-items' , [CartController::class , 'cartItems']);
Route::patch('/cart-items-updateQty/{id}' , [CartController::class , 'updateCartProductQty']);
Route::delete('/cart-item-delete/{id}' , [CartController::class , 'deleteCartProduct']);
