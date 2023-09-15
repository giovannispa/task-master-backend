<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;

/**
 * Controller TaskController
 *
 * Controlador responsável por todas as interações envolvendo tarefas.
 */
class TaskController extends Controller
{
    /**
     * @var TaskService
     */
    private TaskService $taskService;

    /**
     * Construtor.
     *
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Action que retorna todas as tarefas.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $tasks = $this->taskService->all();

        return TaskResource::collection($tasks);
    }


    public function create()
    {
        //
    }

    /**
     * Action que salva uma tarefa no banco.
     *
     * @param TaskRequest $request
     * @return TaskResource
     */
    public function store(TaskRequest $request): TaskResource
    {
        $task = $this->taskService->create($request->validated());

        return new TaskResource($task);
    }

    /**
     * Action que retorna 1 task especifica.
     *
     * @param string $id
     * @return TaskResource
     */
    public function show(string $id): TaskResource
    {
        $task = $this->taskService->find($id);

        return new TaskResource($task);
    }


    public function edit(string $id)
    {
        //
    }

    /**
     * Action que atualiza uma tarefa.
     *
     * @param TaskRequest $request
     * @param string $id
     * @return TaskResource
     */
    public function update(TaskRequest $request, string $id): TaskResource
    {
        $task = $this->taskService->update($id, $request->validated());

        return new TaskResource($task);
    }

    /**
     * Action que deleta uma tarefa.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): \Illuminate\Http\Response
    {
        $this->taskService->delete($id);

        return response()->noContent();
    }
}
