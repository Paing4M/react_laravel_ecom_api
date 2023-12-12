<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
  public function register(Request $request, User $user) {
    $payload = $request->only(['name', 'password', 'email', 'password_confirmation']);

    $validator = Validator::validate($payload, [
      'name' => 'required',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:8',
      'password_confirmation' => 'required|same:password',
    ], [
      'password_confirmation.same' => 'Password does not match.'
    ]);

    $user = $user->create([
      'name' => $validator['name'],
      'email' => $validator['email'],
      'password' => Hash::make($validator['password']),
    ]);

    if ($user)
      $token = $user->createToken($user->name)->plainTextToken;

    return response()->json(
      [
        'user' => $user,
        'token' => $token,
        'message' => 'Register successful.',
      ],
      201
    );
  }

  public function login(Request $request) {
    $payload = $request->only(['email', 'password']);

    $validator = Validator::validate($payload, [
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $user = User::where('email', $validator['email'])->first();

    if (!$user || !Hash::check($validator['password'], $user->password)) {
      return response()->json([
        'incorrect' => 'Email or password incorrect.'
      ], '401');
    } else {

      if ($user->type === 1) {
        $token = $user->createToken($user->name . '_adminToken', ['server:admin'])->plainTextToken;
      } else {
        $token = $user->createToken($user->name . '_userToken', [''])->plainTextToken;
      }


      return response()->json([
        'token' => $token,
        'user' => $user
      ]);
    }


  }

  public function logout() {
    auth()->user()->tokens()->delete();
    return response()->json([
      'message' => 'Successfully logged out.'
    ]);
  }
}

