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
     * @return mixed
     */
    public function findFirst(string $column, mixed $value)
    {
        return $this->repository->findFirst($column, $value);
    }

    /**
     * Retorna todos os usuários.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->repository->all();
    }

    /**
     * Cria um novo usuário.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Encontra um usuário pelo ID.
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * Deleta um usuário pelo ID.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Atualiza um usuário pelo ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed|null
     */
    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }
}
