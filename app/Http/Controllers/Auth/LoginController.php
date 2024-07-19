<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __construct()
    {
        // Применяем middleware 'guest' ко всем методам контроллера, кроме 'logout'
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        // Валидация входящих данных
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Если валидация не прошла
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Попытка аутентификации
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Регистрация сессии и создание токена
            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }

        // Если аутентификация не удалась
        return response()->json(['errors' => ['email' => ['These credentials do not match our records.']]], 422);
    }

    public function logout(Request $request)
    {
        // Выход из системы и удаление сессии
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully!'], 200);
    }

    public function showLoginForm(Request $request)
    {
        // Отображение формы входа
        return view('auth.login');
    }
}
