<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controller AuthController
 *
 * Controlador responsável métodos de autenticação e recuperação de senha da plataforma.
 */
class AuthController extends Controller
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * Construtor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Action de login do usuário via sanctum.
     *
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(AuthRequest $request): \Illuminate\Http\JsonResponse
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

    /**
     * Action que desloga o usuário da plataforma.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Action que retorna todos os dados do usuário logado.
     *
     * @return UserResource
     */
    public function me(): UserResource
    {
       $user = auth()->user();

        return new UserResource($user);
    }
}
