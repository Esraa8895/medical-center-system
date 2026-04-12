<?php

namespace App\Modules\Auth\Services;

use App\Core\Enums\RoleEnum;
use App\Modules\Auth\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

        $user->tokens()->delete();

        $token = $user->createToken('clinic_token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => $user
        ];
    }

    public function me()
    {
        $user = auth('api')->user();
        if (!$user) {
            throw new Exception("المستخدم غير صحيح");
        }
        return $user;
    }

    public function logout()
    {
        $user = auth('api')->user();
        if ($user) {
            $user->currentAccessToken()->delete();
        }
    }

    public function refreshToken()
    {
        $user = auth('api')->user();
        if (!$user) {
            throw new Exception("توكن غير صالح أو منتهي الصلاحية");
        }

        $currentToken = $user->currentAccessToken();
        if (!$currentToken) {
            throw new Exception("لا يوجد توكن حالي");
        }

        $newToken = $user->createToken('clinic_token')->plainTextToken;
        $currentToken->delete();

        return [
            'token' => $newToken,
            'user'  => $user
        ];
    }


    public function sendResetPasswordLink(array $data)
    {
        $response = Password::sendResetLink($data);

        if ($response === Password::RESET_LINK_SENT) {
            return ['message' => 'تم إرسال رابط إعادة تعيين كلمة المرور'];
        }

        throw new Exception('فشل في إرسال رابط إعادة التعيين');
    }

    public function resetPassword(array $data)
    {
        $response = Password::reset($data, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();
            $user->tokens()->delete();
        });

        if ($response === Password::PASSWORD_RESET) {
            return ['message' => 'تم إعادة تعيين كلمة المرور بنجاح'];
        }

        throw new Exception('فشل في إعادة تعيين كلمة المرور');
    }

    public function changePassword(array $data)
    {
        $user = auth('api')->user();
        if (!$user) {
            throw new Exception("المستخدم غير مصدق عليه");
        }

        if (!Hash::check($data['current_password'], $user->password)) {
            throw new Exception("كلمة المرور الحالية غير صحيحة");
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();
        $user->tokens()->delete();

        return ['message' => 'تم تغيير كلمة المرور بنجاح'];
    }
}


