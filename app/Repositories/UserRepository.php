<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Construtor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Função que resgata todos os usuários do banco.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->model->with(['category','teams'])->get()->toArray();
    }

    /**
     * Função que resgata um usuário especifico por ID.
     *
     * @param int $id
     * @param bool $loadRelationships
     * @return object
     */
    public function find(int $id, bool $loadRelationships = false): object
    {
        return $this->model->with($loadRelationships ? ['category','teams'] : [])->find($id);
    }
}
