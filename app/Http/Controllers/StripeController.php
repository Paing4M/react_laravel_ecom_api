<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StripeController extends Controller
{
    public function checkout(Request $request){
      if(auth('sanctum')->check()){
        $cartItems = Cart::where('user_id' , auth('sanctum')->user()->id)->get();

        $lineItems = [];
       foreach ($cartItems as $item) {
         $unitAmount = ($item->product->selling_price ?? $item->product->original_price) * 100 ;

         $lineItems[] = [
           'price_data' => [
             'currency' =>'usd' ,
             'unit_amount' => $unitAmount,
             'product_data' =>[
               'name' => $item->product->name
             ]
           ],
           'quantity' => $item->product_qty,
         ];
       }

      Stripe::setApiKey(config('stripe.secret_key'));
      $session = Session::create([
        'customer_creation'=>'always',
        'payment_method_types'=>['card' , 'cashapp' ,'us_bank_account'],
        'mode' =>'payment',
        'line_items'=>$lineItems,
        'success_url'=>'http://localhost:5173/cart?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'=>'http://localhost:5173/cart?success=false',
      ]);


      $order = new Order();
      $order->user_id = auth('sanctum')->user()->id;
      $order->session_id = $session->id;
      $order->status = 'unpaid';
      $order->total_price = $request->totalPrice;
      $order->save();

      return response()->json([
        'url' => $session->url
      ]);

      } else {
        return response()->json([
          'status' => 401,
          'message' => 'Please login first.'
        ]);
      }
    }

   public function checkSuccessPayment(Request $request){
      $stripe = new \Stripe\StripeClient(config('stripe.secret_key'));
      $session_id = $request->session_id;
      try{
        $session = $stripe->checkout->sessions->retrieve($session_id);

        $order = Order::where('session_id', $session_id)->where('status' , 'unpaid')->first();
        if($order){
          $order->status='paid';
          $order->save();

          // reduce the qty of product after payment is done
          $cartItems = Cart::where('user_id' , auth('sanctum')->user()->id)->get();
          foreach ($cartItems as $item){
            if($item->product->qty>0){
              $qty = $item->product->qty - $item->product_qty;
              $item->product->update(['qty' => $qty]);
            }
          }
        }


        $customer = $stripe->customers->retrieve($session->customer);
        return response()->json([
          'customer' => $customer,
        ]);
      }  catch (\Exception $e) {
        return response()->json([
          'status' => 404,
          'message' => '404 Not Found.'
        ]);
      }

   }
}
