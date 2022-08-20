<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
            'email.unique' => 'An account with this email already exists',
            'password.required' => 'Password is required',
        ]);


        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => "Registration Successful",
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    //Login a user
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
             'message' => 'Login Successful',
              'access_token' => $token,
              'token_type' => 'Bearer',
              'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
              ]
         ]);
   }

   //Request Reset Link
    public function forgotPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => __($status),
            ]);
        }

        return response()->json([
            'message' => 'Error sending password reset link'
        ], 500);
    }


    //Reset Password
    public function resetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json([
                'message' => "Password reset successfully",
            ]);
        }

        return response()->json([
            'message' => __($status),
        ], 500);
    }
}
