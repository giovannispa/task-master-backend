<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Facades\Cache;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    /**
     * Construtor.
     *
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    /**
     * Função que resgata todas as tarefas do banco.
     *
     * @return array
     */
    public function all(): array
    {
        return Cache::rememberForever(class_basename($this->model::class), function() {
            return $this->model->with(['priority', 'status','project'])->get()->toArray();
        });
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
        return $this->model->with($loadRelationships ? ['priority', 'status','project'] : [])->find($id);
    }
}
