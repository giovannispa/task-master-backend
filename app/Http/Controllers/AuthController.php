<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(AuthRequest $request)
    {
        $user = $this->userService->findFirst('email', $request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
           'token' => $token
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function me()
    {
       $user = auth()->user();


    }
}
