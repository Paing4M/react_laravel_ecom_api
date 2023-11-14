<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminApiMiddleware {
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response {
    if (Auth::check()) {
      if (Auth::user()->tokenCan('server:admin')) {
        return $next($request);
      } else {
        return response()->json(['message' => 'Access denied.'], 403);
      }
    } else {
      return response()->json(['message' => 'Please login first.'], 401);
    }
  }
}
