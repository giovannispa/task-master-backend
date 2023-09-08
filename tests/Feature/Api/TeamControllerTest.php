<?php

namespace Api;

use App\Models\Category;
use App\Models\Team;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Api\UtilsTrait;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
    use UtilsTrait;

    /**
     * @var string
     */
    protected string $endpoint = '/teams';

    /**
     * Testando a listagem de todas as equipes.
     *
     * @return void
     */
    public function test_get_all(): void
    {
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();
        $teams = Team::factory()->count(3)->create([
            'leader_id' => $user->id
        ]);

        $token = $this->createToken();

        $response = $this->getJson($this->endpoint,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');

        $response->assertJson(function (AssertableJson $json) use($teams) {
            $json->whereAllType([
                'data.0.id' => 'integer',
                'data.0.name' => 'string',
                'data.0.leader_id' => 'integer',
                'data.0.active_projects' => 'integer',
                'data.0.worked_projects' => 'integer',
                'data.0.tasks_performed' => 'integer',
            ]);

            $json->hasAll([
                'data.0.id',
                'data.0.name',
                'data.0.leader_id',
                'data.0.active_projects',
                'data.0.worked_projects',
                'data.0.tasks_performed',
            ]);

            $team = $teams->first();

            $json->whereAll([
                'data.0.id' => $team->id,
                'data.0.name' => $team->name,
                'data.0.leader_id' => $team->leader_id,
                'data.0.active_projects' => $team->active_projects,
                'data.0.worked_projects' => $team->worked_projects,
                'data.0.tasks_performed' => $team->tasks_performed
            ]);

        });
    }

    /**
     * Testando a listagem de 1 equipe especifica.
     *
     * @return void
     */
    public function test_find_single(): void
    {
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();
        $team = Team::factory(1)->create([
            'leader_id' => $user->id
        ]);

        $token = $this->createToken();

        $response = $this->getJson("{$this->endpoint}/{$team[0]->id}",[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'leader_id',
                'active_projects',
                'worked_projects',
                'tasks_performed'
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($team) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.leader_id' => 'integer',
                'data.active_projects' => 'integer',
                'data.worked_projects' => 'integer',
                'data.tasks_performed' => 'integer',
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.leader_id',
                'data.active_projects',
                'data.worked_projects',
                'data.tasks_performed'
            ]);

            $json->whereAll([
                'data.id' => $team[0]->id,
                'data.name' => $team[0]->name,
                'data.leader_id' => $team[0]->leader_id,
                'data.active_projects' => $team[0]->active_projects,
                'data.worked_projects' => $team[0]->worked_projects,
                'data.tasks_performed' => $team[0]->tasks_performed,
            ]);

        });
    }

    /**
     * Teste de criação de equipe.
     *
     * @param array $payload
     * @param int $code
     * @param array $structure
     * @param array $jsonWhere
     * @dataProvider dataProviderCreate
     * @return void
     */
    public function test_create(
        array $payload,
        int $code,
        array $structure,
        array $jsonWhere
    ): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();

        $payload['leader_id'] = $user->id;

        $response = $this->postJson($this->endpoint, $payload,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus($code);
        $response->assertJsonStructure($structure);

        $response->assertJson(function (AssertableJson $json) use($payload, $jsonWhere) {

            $json->whereAll($jsonWhere);

        });
    }

    /**
     * Teste de atualização de equipe.
     *
     * @param array $payload
     * @param int $code
     * @param array $structure
     * @param array $jsonWhere
     * @dataProvider dataProviderUpdate
     * @return void
     */
    public function test_update(
        array $payload,
        int $code,
        array $structure,
        array $jsonWhere
    ): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();
        $team = Team::factory()->create([
            'leader_id' => $user->id
        ]);

        $payload['leader_id'] = $user->id;

        $response = $this->putJson("{$this->endpoint}/{$team->id}", $payload,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus($code);
        $response->assertJsonStructure($structure);

        $response->assertJson(function (AssertableJson $json) use($payload, $jsonWhere) {

            $json->whereAll($jsonWhere);

        });
    }

    /**
     * Teste que adiciona um membro a equipe.
     *
     * @return void
     */
    public function test_add_one_coworker(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();
        $team = Team::factory(1)->create([
            'leader_id' => $user->id
        ]);

        $response = $this->postJson("{$this->endpoint}/{$team[0]->id}/coworkers", [
            'user_id' => $user->id
        ],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'leader_id',
                'active_projects',
                'worked_projects',
                'tasks_performed'
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($team) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.leader_id' => 'integer',
                'data.active_projects' => 'integer',
                'data.worked_projects' => 'integer',
                'data.tasks_performed' => 'integer',
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.leader_id',
                'data.active_projects',
                'data.worked_projects',
                'data.tasks_performed'
            ]);

            $json->whereAll([
                'data.id' => $team[0]->id,
                'data.name' => $team[0]->name,
                'data.leader_id' => $team[0]->leader_id,
                'data.active_projects' => $team[0]->active_projects,
                'data.worked_projects' => $team[0]->worked_projects,
                'data.tasks_performed' => $team[0]->tasks_performed,
            ]);

        });
    }

    /**
     * Teste que adiciona vários membros a equipe.
     *
     * @return void
     */
    public function test_add_many_coworker(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $userAux = User::factory()->for($category)->create();
        $team = Team::factory(1)->create([
            'leader_id' => $userAux->id
        ]);

        $users = User::factory()->count(5)->for($category)->create();

        $payload = [];
        foreach($users as $user) {
            $payload[] = $user->id;
        }

        $response = $this->postJson("{$this->endpoint}/{$team[0]->id}/coworkers", [
            'user_id' => $payload
        ],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'leader_id',
                'active_projects',
                'worked_projects',
                'tasks_performed'
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($team) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.leader_id' => 'integer',
                'data.active_projects' => 'integer',
                'data.worked_projects' => 'integer',
                'data.tasks_performed' => 'integer',
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.leader_id',
                'data.active_projects',
                'data.worked_projects',
                'data.tasks_performed'
            ]);

            $json->whereAll([
                'data.id' => $team[0]->id,
                'data.name' => $team[0]->name,
                'data.leader_id' => $team[0]->leader_id,
                'data.active_projects' => $team[0]->active_projects,
                'data.worked_projects' => $team[0]->worked_projects,
                'data.tasks_performed' => $team[0]->tasks_performed,
            ]);

        });
    }

    /**
     * Teste que remove um membro da equipe.
     *
     * @return void
     */
    public function test_remove_one_coworker(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $userAux = User::factory()->for($category)->create();
        $team = Team::factory(1)->create([
            'leader_id' => $userAux->id
        ]);

        $users = User::factory()->count(5)->for($category)->create();

        $payload = [];
        foreach($users as $user) {
            $payload[] = $user->id;
        }

        $this->postJson("{$this->endpoint}/{$team[0]->id}/coworkers", [
            'user_id' => $payload
        ],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response = $this->deleteJson("{$this->endpoint}/{$team[0]->id}/coworkers", [
            'user_id' => $payload[3]
        ],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'leader_id',
                'active_projects',
                'worked_projects',
                'tasks_performed'
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($team) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.leader_id' => 'integer',
                'data.active_projects' => 'integer',
                'data.worked_projects' => 'integer',
                'data.tasks_performed' => 'integer',
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.leader_id',
                'data.active_projects',
                'data.worked_projects',
                'data.tasks_performed'
            ]);

            $json->whereAll([
                'data.id' => $team[0]->id,
                'data.name' => $team[0]->name,
                'data.leader_id' => $team[0]->leader_id,
                'data.active_projects' => $team[0]->active_projects,
                'data.worked_projects' => $team[0]->worked_projects,
                'data.tasks_performed' => $team[0]->tasks_performed,
            ]);

        });
    }

    /**
     * Data provider de criação de equipe.
     *
     * @return array[]
     */
    protected function dataProviderCreate(): array
    {
        return [
            'creating with all data' => [
                'payload' => [
                    'name' => 'Testando',
                    'active_projects' => 2,
                    'worked_projects' => 10,
                    'tasks_performed' => 50,
                ],
                'code' => 201,
                'structure' => [
                    'data' => ['name', 'active_projects', 'worked_projects', 'tasks_performed']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando',
                    'data.active_projects' => 2,
                    'data.worked_projects' => 10,
                    'data.tasks_performed' => 50
                ]
            ],
            'creating without tasks performed' => [
                'payload' => [
                    'name' => 'Testando',
                    'active_projects' => 2,
                    'worked_projects' => 10,
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The tasks performed field is required.',
                    'errors' => ['tasks_performed' => ['The tasks performed field is required.']]
                ]
            ]
        ];
    }

    /**
     * Teste de exclusão de equipe.
     *
     * @return void
     */
    public function test_delete(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();
        $team = Team::factory()->create([
            'leader_id' => $user->id
        ]);

        $response = $this->deleteJson("{$this->endpoint}/{$team->id}",[],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(204);
        $response->assertNoContent();
    }

    /**
     * Data provider de atualização de equipe.
     *
     * @return array[]
     */
    protected function dataProviderUpdate(): array
    {
        return [
            'updating with all data' => [
                'payload' => [
                    'name' => 'Testando',
                    'active_projects' => 2,
                    'worked_projects' => 10,
                    'tasks_performed' => 50,
                ],
                'code' => 200,
                'structure' => [
                    'data' => ['name', 'active_projects', 'worked_projects', 'tasks_performed']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando',
                    'data.active_projects' => 2,
                    'data.worked_projects' => 10,
                    'data.tasks_performed' => 50
                ]
            ],
            'updating without tasks performed' => [
                'payload' => [
                    'name' => 'Testando',
                    'active_projects' => 2,
                    'worked_projects' => 10
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The tasks performed field is required.',
                    'errors' => ['tasks_performed' => ['The tasks performed field is required.']]
                ]
            ]
        ];
    }
}
