<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamStoreCoworker;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Http\Resources\TeamResource;
use App\Services\TeamService;

/**
 * Controller TeamController
 *
 * Controlador responsável por todas as interações envolvendo equipes.
 */
class TeamController extends Controller
{
    /**
     * @var TeamService
     */
    private TeamService $teamService;

    /**
     * Construtor.
     *
     * @param TeamService $teamService
     */
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Action que retorna todos as equipes.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $teams = $this->teamService->all();

        return TeamResource::collection($teams);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Action que cadastra uma equipe no sistema.
     *
     * @param TeamStoreRequest $request
     * @return TeamResource
     */
    public function store(TeamStoreRequest $request): TeamResource
    {
        $team = $this->teamService->create($request->validated());

        return new TeamResource($team);
    }

    /**
     * Action que lista 1 equipe especifica por ID.
     *
     * @param string $id
     * @return TeamResource
     */
    public function show(string $id): TeamResource
    {
        $team = $this->teamService->find($id, true);

        return new TeamResource($team);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Action que atualiza uma equipe
     *
     * @param TeamUpdateRequest $request
     * @param string $id
     * @return TeamResource
     */
    public function update(TeamUpdateRequest $request, string $id): TeamResource
    {
        $team = $this->teamService->update($id, $request->validated());

        return new TeamResource($team);
    }

    /**
     * Action que deleta uma equipe.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): \Illuminate\Http\Response
    {
        $this->teamService->delete($id);

        return response()->noContent();
    }

    /**
     * Action que adiciona um membro ou vários a equipe.
     *
     * @param TeamStoreCoworker $request
     * @param string $id
     * @return TeamResource
     */
    public function storeCoworker(TeamStoreCoworker $request, string $id): TeamResource
    {
        if($team = $this->teamService->find($id)) {
            if(!is_array($request->user_id)) {
                $this->teamService->attach('users', $team->id, $request->user_id);
            } else {
                foreach($request->user_id as $user_id) {
                    $this->teamService->attach('users', $team->id, $user_id);
                }
            }

            return new TeamResource($team);
        }

        return response()->json([
           'error' => 'Team not found'
        ], 404);
    }

    /**
     * Action que remove 1 membro ou vários da equipe.
     *
     * @param TeamStoreCoworker $request
     * @param string $id
     * @return TeamResource
     */
    public function destroyCoworker(TeamStoreCoworker $request, string $id): TeamResource
    {
        if($team = $this->teamService->find($id)) {
            if(!is_array($request->user_id)) {
                $this->teamService->detach('users', $team->id, $request->user_id);
            } else {
                foreach($request->user_id as $user_id) {
                    $this->teamService->detach('users', $team->id, $user_id);
                }
            }

            return new TeamResource($team);
        }

        return response()->json([
            'error' => 'Team not found'
        ], 404);
    }
}
