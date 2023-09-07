<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;

/**
 * Classe UserService
 *
 * Esta classe é um serviço responsável por manipular os dados de usuários.
 * Ela depende de uma implementação do repositório do usuário
 * para realizar as operações de CRUD.
 */
class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * Construtor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * Encontra o primeiro usuário que corresponda a um valor específico de uma coluna.
     *
     * @param string $column
     * @param mixed $value
     * @return object
     */
    public function findWhereFirst(string $column, mixed $value): object
    {
        return $this->repository->findWhereFirst($column, $value);
    }

    /**
     * Retorna todos os usuários.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->repository->all();
    }

    /**
     * Cria um novo usuário.
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->repository->create($data);
    }

    /**
     * Encontra um usuário pelo ID.
     *
     * @param int $id
     * @return object
     */
    public function find(int $id): object
    {
        return $this->repository->find($id);
    }

    /**
     * Deleta um usuário pelo ID.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Atualiza um usuário pelo ID.
     *
     * @param int $id
     * @param array $data
     * @return object
     */
    public function update(int $id, array $data): object
    {
        return $this->repository->update($id, $data);
    }
}
