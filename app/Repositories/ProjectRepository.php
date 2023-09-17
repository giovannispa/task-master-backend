<?php

namespace App\Repositories;

use App\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    /**
     * Construtor.
     *
     * @param Project $model
     */
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    /**
     * Função que resgata todos os projetos do banco.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->model->with(['teams', 'status'])->get()->toArray();
    }

    /**
     * Função que resgata um projeto especifico por ID.
     *
     * @param int $id
     * @param bool $loadRelationships
     * @return object
     */
    public function find(int $id, bool $loadRelationships = false): object
    {
        return $this->model->with($loadRelationships ? ['teams', 'status'] : [])->find($id);
    }
}
