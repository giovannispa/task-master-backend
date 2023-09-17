<?php

namespace App\Repositories;

use App\Interfaces\TeamRepositoryInterface;
use App\Models\Team;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{
    /**
     * Construtor.
     *
     * @param Team $model
     */
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }

    /**
     * Função que resgata todas as equipes do banco.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->model->with(['users','projects'])->get()->toArray();
    }

    /**
     * Função que resgata uma equipe especifica por ID.
     *
     * @param int $id
     * @param bool $loadRelationships
     * @return object
     */
    public function find(int $id, bool $loadRelationships = false): object
    {
        return $this->model->with($loadRelationships ? ['users','projects'] : [])->find($id);
    }
}
