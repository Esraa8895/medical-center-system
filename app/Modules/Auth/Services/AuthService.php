<?php

namespace App\Modules\Auth\Services;

use App\Core\Enums\RoleEnum;
use App\Modules\Auth\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data)
    {
      $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        $user->assignRole(RoleEnum::RECEPTIONIST->value);
        return $user;
    }

    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new Exception("بيانات الدخول غير صحيحة");
        }

        $token = $user->createToken('clinic_token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => $user
        ];
    }

    public function me()
    {
        return auth('api')->user();
    }

    public function logout()
    {
        auth('api')->user()->currentAccessToken()->delete();
    }
}


