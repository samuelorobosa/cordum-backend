<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Register a user
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ],[
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'This user already exists',
            'password.required' => 'Password is required',
        ]);


        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

   public function login(Request $request): \Illuminate\Http\JsonResponse
   {
         $validatedData = $request->validate([
              'email' => 'required|string|email|max:255',
              'password' => 'required|string|min:6',
         ]);

         $user = User::where('email', $validatedData['email'])->first();

         if (!$user) {
              return response()->json([
                'message' => 'User not found'
              ], 404);
         }

         if (!Hash::check($validatedData['password'], $user->password)) {
              return response()->json([
                'message' => 'Password is incorrect'
              ], 401);
         }


         $token = $user->createToken('auth_token')->plainTextToken;

         return response()->json([
              'access_token' => $token,
              'token_type' => 'Bearer',
              'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
              ]
         ]);
   }
}
