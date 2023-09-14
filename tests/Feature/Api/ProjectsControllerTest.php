<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Project;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProjectsControllerTest extends TestCase
{
    use UtilsTrait;

    /**
     * @var string
     */
    protected string $endpoint = '/projects';

    /**
     * Testando a listagem de todos os projetos.
     *
     * @return void
     */
    public function test_get_all(): void
    {
        $status = Status::factory()->create();
        $projects = Project::factory()->count(3)->for($status)->create();

        $token = $this->createToken();

        $response = $this->getJson($this->endpoint,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');

        $response->assertJson(function (AssertableJson $json) use($projects) {
            $json->whereAllType([
                'data.0.id' => 'integer',
                'data.0.name' => 'string',
                'data.0.status_id' => 'integer',
                'data.0.description' => 'string',
                'data.0.start_date' => 'string',
                'data.0.deadline' => 'string',
                'data.0.end_date' => 'string',
            ]);

            $json->hasAll([
                'data.0.id',
                'data.0.name',
                'data.0.status_id',
                'data.0.description',
                'data.0.start_date',
                'data.0.deadline',
                'data.0.end_date',
            ]);

            $project = $projects->first();

            $json->whereAll([
                'data.0.id' => $project->id,
                'data.0.name' => $project->name,
                'data.0.status_id' => $project->status_id,
                'data.0.description' => $project->description,
                'data.0.start_date' => $project->start_date,
                'data.0.deadline' => $project->deadline,
                'data.0.end_date' => $project->end_date
            ]);

        });
    }

    /**
     * Testando a listagem de 1 projeto especifico.
     *
     * @return void
     */
    public function test_find_single(): void
    {
        $status = Status::factory()->create();
        $project = Project::factory()->count(1)->for($status)->create();

        $token = $this->createToken();

        $response = $this->getJson("{$this->endpoint}/{$project[0]->id}",[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'status_id',
                'description',
                'start_date',
                'deadline',
                'end_date'
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($project) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.status_id' => 'integer',
                'data.description' => 'string',
                'data.start_date' => 'string',
                'data.deadline' => 'string',
                'data.end_date' => 'string',
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.status_id',
                'data.description',
                'data.start_date',
                'data.deadline',
                'data.end_date'
            ]);

            $json->whereAll([
                'data.id' => $project[0]->id,
                'data.name' => $project[0]->name,
                'data.status_id' => $project[0]->status_id,
                'data.description' => $project[0]->description,
                'data.start_date' => $project[0]->start_date,
                'data.deadline' => $project[0]->deadline,
                'data.end_date' => $project[0]->end_date,
            ]);

        });
    }

    /**
     * Teste de criação de projeto.
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
        $status = Status::factory()->create();

        $payload['status_id'] = $status->id;

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
     * Teste de atualização de projeto.
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
        $status = Status::factory()->create();
        $project = Project::factory()->for($status)->create();

        $payload['status_id'] = $status->id;

        $response = $this->putJson("{$this->endpoint}/{$project->id}", $payload,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus($code);
        $response->assertJsonStructure($structure);

        $response->assertJson(function (AssertableJson $json) use($payload, $jsonWhere) {

            $json->whereAll($jsonWhere);

        });
    }

    /**
     * Teste de exclusão de projeto.
     *
     * @return void
     */
    public function test_delete(): void
    {
        $token = $this->createToken();
        $status = Status::factory()->create();
        $project = Project::factory()->for($status)->create();

        $response = $this->deleteJson("{$this->endpoint}/{$project->id}",[],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(204);
        $response->assertNoContent();
    }

    /**
     * Teste que adiciona vários membros a equipe.
     *
     * @return void
     */
    public function test_add_many_teams(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();
        $teams = Team::factory(1)->count(3)->create([
            'leader_id' => $user->id
        ]);

        $status = Status::factory()->create();
        $project = Project::factory()->count(1)->for($status)->create();

        $payload = [];
        foreach($teams as $team) {
            $payload[] = $team->id;
        }

        $response = $this->postJson("{$this->endpoint}/{$project[0]->id}/teams", [
            'team_id' => $payload
        ],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'status_id',
                'description',
                'start_date',
                'deadline',
                'end_date'
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($project) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.status_id' => 'integer',
                'data.description' => 'string',
                'data.start_date' => 'string',
                'data.deadline' => 'string',
                'data.end_date' => 'string'
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.status_id',
                'data.description',
                'data.start_date',
                'data.deadline',
                'data.end_date'
            ]);

            $json->whereAll([
                'data.id' => $project[0]->id,
                'data.name' => $project[0]->name,
                'data.status_id' => $project[0]->status_id,
                'data.description' => $project[0]->description,
                'data.start_date' => $project[0]->start_date,
                'data.deadline' => $project[0]->deadline,
                'data.end_date' => $project[0]->end_date,
            ]);

        });
    }

    /**
     * Teste que remove um time do projeto.
     *
     * @return void
     */
    public function test_remove_one_team(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();
        $teams = Team::factory(1)->count(5)->create([
            'leader_id' => $user->id
        ]);

        $status = Status::factory()->create();
        $project = Project::factory()->count(1)->for($status)->create();

        $payload = [];
        foreach($teams as $team) {
            $payload[] = $team->id;
        }

        $this->postJson("{$this->endpoint}/{$project[0]->id}/teams", [
            'team_id' => $payload
        ],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response = $this->deleteJson("{$this->endpoint}/{$project[0]->id}/teams", [
            'team_id' => $payload[3]
        ],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'status_id',
                'description',
                'start_date',
                'deadline',
                'end_date'
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($project) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.status_id' => 'integer',
                'data.description' => 'string',
                'data.start_date' => 'string',
                'data.deadline' => 'string',
                'data.end_date' => 'string'
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.status_id',
                'data.description',
                'data.start_date',
                'data.deadline',
                'data.end_date'
            ]);

            $json->whereAll([
                'data.id' => $project[0]->id,
                'data.name' => $project[0]->name,
                'data.status_id' => $project[0]->status_id,
                'data.description' => $project[0]->description,
                'data.start_date' => $project[0]->start_date,
                'data.deadline' => $project[0]->deadline,
                'data.end_date' => $project[0]->end_date,
            ]);

        });
    }

    /**
     * Data provider de criação de projeto.
     *
     * @return array[]
     */
    protected function dataProviderCreate(): array
    {
        return [
            'creating with all data' => [
                'payload' => [
                    'name' => 'Testando',
                    'description' => 'testando descricao',
                    'start_date' => date("Y-m-d"),
                    'deadline' => date("Y-m-d"),
                    'end_date' => date("Y-m-d")
                ],
                'code' => 201,
                'structure' => [
                    'data' => ['name', 'status_id', 'description', 'start_date', 'deadline', 'end_date']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando',
                    'data.status_id' => 3,
                    'data.description' => 'testando descricao',
                    'data.start_date' => date("Y-m-d"),
                    'data.deadline' => date("Y-m-d"),
                    'data.end_date' => date("Y-m-d")
                ]
            ],
            'creating without end date' => [
                'payload' => [
                    'name' => 'Testando',
                    'description' => 'testando descricao',
                    'start_date' => date("Y-m-d"),
                    'deadline' => date("Y-m-d"),
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The end date field is required.',
                    'errors' => ['end_date' => ['The end date field is required.']]
                ]
            ]
        ];
    }

    /**
     * Data provider de atualização de projetos.
     *
     * @return array[]
     */
    protected function dataProviderUpdate(): array
    {
        return [
            'updating with all data' => [
                'payload' => [
                    'name' => 'Testando',
                    'description' => 'testando descricao',
                    'start_date' => date("Y-m-d"),
                    'deadline' => date("Y-m-d"),
                    'end_date' => date("Y-m-d")
                ],
                'code' => 200,
                'structure' => [
                    'data' => ['name', 'status_id', 'description', 'start_date', 'deadline', 'end_date']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando',
                    'data.status_id' => 5,
                    'data.description' => 'testando descricao',
                    'data.start_date' => date("Y-m-d"),
                    'data.deadline' => date("Y-m-d"),
                    'data.end_date' => date("Y-m-d")
                ]
            ],
            'updating without end date' => [
                'payload' => [
                    'name' => 'Testando',
                    'description' => 'testando descricao',
                    'start_date' => date("Y-m-d"),
                    'deadline' => date("Y-m-d"),
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The end date field is required.',
                    'errors' => ['end_date' => ['The end date field is required.']]
                ]
            ]
        ];
    }
}
