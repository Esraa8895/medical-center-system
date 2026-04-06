<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponseTrait;
use App\Modules\Auth\Http\Requests\LoginRequest;
use App\Modules\Auth\Http\Requests\RegisterRequest;
use App\Modules\Auth\Http\Resources\UserResource;
use App\Modules\Auth\Services\AuthService;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return $this->successResponse(new UserResource($user), "تم التسجيل");
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        return $this->successResponse([
            'token' => $data['token'],
            'user'  => new UserResource($data['user'])
        ], "تم تسجيل الدخول");
    }

    public function me()
    {
        $user = $this->authService->me();

        return $this->successResponse(new UserResource($user));
    }

    public function logout()
    {
        $this->authService->logout();

        return $this->successResponse(null, "تم تسجيل الخروج");
    }
}



