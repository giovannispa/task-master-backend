<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectStoreTeam;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;

/**
 * Controller ProjectController
 *
 * Controlador responsável por todas as interações envolvendo projetos.
 */
class ProjectController extends Controller
{
    /**
     * @var ProjectService
     */
    private ProjectService $projectService;

    /**
     * Construtor.
     *
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Action que retorna todos os projetos.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $projects = $this->projectService->all();

        return ProjectResource::collection($projects);
    }

    public function create()
    {
        //
    }

    /**
     * Action que cadastra um projeto.
     *
     * @param ProjectRequest $request
     * @return ProjectResource
     */
    public function store(ProjectRequest $request): ProjectResource
    {
        $project = $this->projectService->create($request->validated());

        return new ProjectResource($project);
    }

    /**
     * Action que retorna um projeto especifico por ID.
     *
     * @param string $id
     * @return ProjectResource
     */
    public function show(string $id): ProjectResource
    {
        $project = $this->projectService->find($id, true);

        return new ProjectResource($project);
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Action que atualiza um projeto.
     *
     * @param ProjectRequest $request
     * @param string $id
     * @return ProjectResource
     */
    public function update(ProjectRequest $request, string $id): ProjectResource
    {
        $project = $this->projectService->update($id, $request->validated());

        return new ProjectResource($project);
    }

    /**
     * Action que deleta um projeto.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): \Illuminate\Http\Response
    {
        $this->projectService->delete($id);

        return response()->noContent();
    }

    /**
     * Action que faz o relacionamento de projeto com times.
     *
     * @param ProjectStoreTeam $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse|ProjectResource
     */
    public function storeTeam(ProjectStoreTeam $request, string $id): \Illuminate\Http\JsonResponse|ProjectResource
    {
        if($project = $this->projectService->find($id)) {
            if(!is_array($request->team_id)) {
                $this->projectService->attach('teams', $project->id, $request->team_id);
            } else {
                foreach($request->team_id as $team_id) {
                    $this->projectService->attach('teams', $project->id, $team_id);
                }
            }

            return new ProjectResource($project);
        }

        return response()->json([
            'error' => 'Project not found'
        ], 404);
    }

    /**
     * Action que remove o relacionamento de projeto com um ou vários times.
     *
     * @param ProjectStoreTeam $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse|ProjectResource
     */
    public function destroyTeam(ProjectStoreTeam $request, string $id): \Illuminate\Http\JsonResponse|ProjectResource
    {
        if($project = $this->projectService->find($id)) {
            if(!is_array($request->team_id)) {
                $this->projectService->detach('teams', $project->id, $request->team_id);
            } else {
                foreach($request->team_id as $team_id) {
                    $this->projectService->detach('teams', $project->id, $team_id);
                }
            }

            return new ProjectResource($project);
        }

        return response()->json([
            'error' => 'Project not found'
        ], 404);
    }
}
