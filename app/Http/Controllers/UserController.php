<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
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
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $users = $this->userService->all();

        return UserResource::collection($users);
    }

    /**
     * Cadastra um novo usuário na plataforma.
     *
     * @param UserStoreRequest $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request): UserResource
    {
        $user = $request->validated();
        $user['password'] = bcrypt($user['password']);
        $user = $this->userService->create($user);

        return new UserResource($user);
    }

    /**
     * Exibe um usuário pelo ID.
     *
     * @param string $id
     * @return UserResource
     */
    public function show(string $id): UserResource
    {
        $user = $this->userService->find($id);

        return new UserResource($user);
    }

    /**
     * Atualiza os dados de um usuário pelo ID.
     *
     * @param UserUpdateRequest $request
     * @param string $id
     * @return UserResource
     */
    public function update(UserUpdateRequest $request, string $id): UserResource
    {
        $user = $request->validated();
        $this->userService->update($id, $user);

        return new UserResource($user);
    }

    /**
     * Remove os dados de um usuário pelo ID.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function destroy(string $id)
    {
        $this->userService->delete($id);

        return response()->noContent();
    }
}
