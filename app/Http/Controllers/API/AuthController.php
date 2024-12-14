<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\Notifications\WelcomeNotification;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Password::defaults()],
            ], [
                'name.required' => 'Imię jest wymagane',
                'name.max' => 'Imię może mieć maksymalnie 255 znaków',
                'email.required' => 'Email jest wymagany',
                'email.email' => 'Podany email jest nieprawidłowy',
                'email.unique' => 'Ten email jest już zajęty',
                'password.required' => 'Hasło jest wymagane',
                'password.confirmed' => 'Hasła nie są identyczne',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Błąd walidacji',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

    	    $user->notify(new WelcomeNotification());

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Wystąpił błąd podczas rejestracji',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'Email jest wymagany',
                'email.email' => 'Podany email jest nieprawidłowy',
                'password.required' => 'Hasło jest wymagane',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Błąd walidacji',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Nieprawidłowy email lub hasło'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Wystąpił błąd podczas logowania',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile()
    {
        try {
            $user = auth()->user();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Nie udało się pobrać profilu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            
            return response()->json([
                'message' => 'Pomyślnie wylogowano'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Wystąpił błąd podczas wylogowywania',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}