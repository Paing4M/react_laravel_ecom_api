<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
      $orders = Order::orderBy('created_at', 'DESC')->paginate($request->per_page ?? 10);
      return response()->json($orders);
    }
}
