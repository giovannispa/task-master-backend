<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * Controller UserController
 *
 * Controlador responsável por todas as interações envolvendo usuários.
 */
class UserController extends Controller
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
     * Retorna todos os usuários.
     */
    public function index(): UserResource
    {
        $users = $this->userService->all();

        return new UserResource($users);
    }

    /**
     * Cadastra um novo usuário na plataforma.
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = $request->validated();
        $user['password'] = bcrypt($user['password']);
        $this->userService->create($user);

        return new UserResource($user);
    }

    /**
     * Exibe um usuário pelo ID.
     */
    public function show(string $id): UserResource
    {
        $user = $this->userService->find($id);

        return new UserResource($user);
    }

    /**
     * Atualiza os dados de um usuário pelo ID.
     */
    public function update(UpdateUserRequest $request, string $id): UserResource
    {
        $user = $request->validated();
        $this->userService->update($id, $user);

        return new UserResource($user);
    }

    /**
     * Remove os dados de um usuário pelo ID.
     */
    public function destroy(string $id)
    {
        if($this->userService->delete($id)) {
            return response()->json([
                'success' => true
            ]);
        }
    }
}
