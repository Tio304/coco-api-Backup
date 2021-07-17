<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
  public function register(Request $request)
  {
    $this->validate($request, [
        "email" => "required|unique:users|max:255",
        "password" => "required|min:6"
    ]);


      $email = $request->input("email");
      $password = Hash::make($request->input("password"));

      $data = User::create([
          "email" => $email,
          "password" => $password,
      ]);

      return response()->json(['message' => 'Data added successfully'], 201);
  }
  public function login(Request $request)
  {
    $this->validate($request, [
        "email" => "required",
        "password" => "required|min:6",
    ]);

    $email = $request->input("email");
    $password = $request->input("password");

    $user = User::where("email", $email)->first();

      if (!$user) {
          return response()->json(['message' => 'Login failed'], 401);
      }

      $isValidPassword = Hash::check($password, $user->password);
      if (!$isValidPassword) {
        return response()->json(['message' => 'Login failed'], 401);
      }

      $generateToken = bin2hex(random_bytes(40));
      $user->update([
          'token' => $generateToken
      ]);

      return response()->json($user);
  }
  function generateRandomString($length = 80)
  {
      $karakter = "012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $panjang_karakter = strlen($karakter);
      $str ="";
      for ($i = 0; $i < $length; $i++) {
          $str .= $karakter[rand(0, $panjang_karakter - 1)];
      }
      return $str;
  }
} 