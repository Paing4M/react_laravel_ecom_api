<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {
  public function addToCart(Request $request) {
    if (auth('sanctum')->check()) {
      $user_id = auth('sanctum')->user()->id;
      $product_id = $request->product_id;
      $product_qty = $request->product_qty;
      // check product
      $checkProduct = Product::query()->where('id', $product_id)->first();

      if ($checkProduct) {
          // if already exit in cart increase the qty
          $cartProduct = Cart::query()->where('product_id' , $product_id)->where('user_id' , $user_id)->first();

          if($cartProduct){
            $cartProduct->product_qty = $product_qty + $cartProduct->product_qty;
            $cartProduct->save();
            return response()->json([
              'status' => 201,
              'message' => 'Successfully added to cart.'
            ]);
          } else {
              $cart = new Cart();
              $cart->user_id = $user_id;
              $cart->product_id = $product_id;
              $cart->product_qty = $product_qty;
              $cart->save();
              return response()->json([
                'status' => 201,
                'message' => 'Successfully added to cart.',
              ]);
          }

      } else {
        return response()->json([
          'status' => 404,
          'message' => 'Product is not found.'
        ]);
      }
    } else {
      return response()->json([
        'status' => 401,
        'message' => 'Please login first.'
      ]);
    }
  }

  public function cartItems () {
    if(auth('sanctum')->check()){
      $cartItems = Cart::where('user_id' , auth('sanctum')->user()->id)->get();
      return response()->json([
        'cart' => $cartItems
      ]);
    } else {
      return response()->json([
        'status' => 401,
        'message' => 'Please login first.'
      ]);
    }
  }

  public function updateCartProductQty (Request $request) {
    if(auth('sanctum')->check()){
      $cartItem = Cart::where('id' , $request->id)->where('user_id' , auth('sanctum')->user()->id)->first();

      $cartItem->product_qty = $request->product_qty;
      $cartItem->save();

      return response()->json([
        'cart' => $cartItem,
        'status'=>200
      ]);
    } else {
      return response()->json([
        'status' => 401,
        'message' => 'Please login first.'
      ]);
    }
  }

  public function deleteCartProduct(Request $request) {
    if(auth('sanctum')->check()){
     $cartProduct = Cart::where('id' , $request->id)->where('user_id' , auth('sanctum')->user()->id)->first();
     $cartProduct->delete();
      return response()->json([
        'status' => 200,
        'message' => 'Product deleted successfully.'
      ]);
    } else {
      return response()->json([
        'status' => 401,
        'message' => 'Please login first.'
      ]);
    }
  }

  public function deleteCart(Request $request)
  {
    $deleted = Cart::where('user_id', auth('sanctum')->user()->id)->delete();
    if($deleted) {
      return response()->json([
        'message' => 'Success deleted.',
        'status' => 200
      ]);
    }
  }
}
