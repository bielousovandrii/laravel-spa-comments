<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    protected function create(array $data)
    {
        $path = null;
        if (isset($data['photo'])) {
            $path = $data['photo']->store('avatars', 'public');
        }

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'photo' => $path,
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo');
        }

        event(new Registered($user = $this->create($data)));

        return response()->json(['message' => 'Registration successful!'], 201);
    }
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
