<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }

    public function loginUser(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            abort(401, 'Email & Password does not match with our records.');
        }

        $user = User::where('email', $credentials['email'])->first();
        $user->tokens()->delete();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->createToken('API_TOKEN')->plainTextToken
        ];
    }

    public function logOutUser($user)
    {
        $user->tokens()->delete();
    }
}
