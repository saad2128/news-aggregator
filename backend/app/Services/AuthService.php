<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function registerUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->authRepository->createUser($data);
    }

    public function loginUser(array $credentials)
    {
        return $this->authRepository->loginUser($credentials);
    }

    public function logOutUser($user)
    {
        return $this->authRepository->logOutUser($user);
    }
}
