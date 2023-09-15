<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;

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
}
