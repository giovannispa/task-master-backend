<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TasksControllerTest extends TestCase
{
    use UtilsTrait;

    /**
     * @var string
     */
    protected string $endpoint = '/tasks';

    /**
     * Testando a listagem de todas as tarefas.
     *
     * @return void
     */
    public function test_get_all(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $userCreatedBy = User::factory()->for($category)->create();
        $userAssignedTo = User::factory()->for($category)->create();
        $status = Status::factory()->create();
        $priority = Priority::factory()->create();
        $project = Project::factory()->for($status)->create();

        $tasks = Task::factory()->count(3)->for($status)->for($priority)->for($project)->create([
            'created_by' => $userCreatedBy->id,
            'assigned_to' => $userAssignedTo->id
        ]);

        $response = $this->getJson($this->endpoint,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');

        $response->assertJson(function (AssertableJson $json) use($tasks) {
            $json->whereAllType([
                'data.0.id' => 'integer',
                'data.0.name' => 'string',
                'data.0.description' => 'string',
                'data.0.created_by' => 'integer',
                'data.0.assigned_to' => 'integer',
                'data.0.status_id' => 'integer',
                'data.0.priority_id' => 'integer',
                'data.0.start_date' => 'string',
                'data.0.end_date' => 'string',
            ]);

            $json->hasAll([
                'data.0.id',
                'data.0.name',
                'data.0.description',
                'data.0.created_by',
                'data.0.assigned_to',
                'data.0.status_id',
                'data.0.priority_id',
                'data.0.start_date',
                'data.0.end_date'
            ]);

            $task = $tasks->first();

            $json->whereAll([
                'data.0.id' => $task->id,
                'data.0.name' => $task->name,
                'data.0.description' => $task->description,
                'data.0.created_by' => $task->created_by,
                'data.0.assigned_to' => $task->assigned_to,
                'data.0.status_id' => $task->status_id,
                'data.0.priority_id' => $task->priority_id,
                'data.0.start_date' => $task->start_date,
                'data.0.end_date' => $task->end_date
            ]);

        });
    }

    /**
     * Testando a listagem de 1 tarefa especifica.
     *
     * @return void
     */
    public function test_find_single(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $userCreatedBy = User::factory()->for($category)->create();
        $userAssignedTo = User::factory()->for($category)->create();
        $status = Status::factory()->create();
        $priority = Priority::factory()->create();
        $project = Project::factory()->for($status)->create();

        $task = Task::factory()->count(1)->for($status)->for($priority)->for($project)->create([
            'created_by' => $userCreatedBy->id,
            'assigned_to' => $userAssignedTo->id
        ]);

        $response = $this->getJson("{$this->endpoint}/{$task[0]->id}",[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(12, 'data');

        $response->assertJson(function (AssertableJson $json) use($task) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.description' => 'string',
                'data.created_by' => 'integer',
                'data.assigned_to' => 'integer',
                'data.status_id' => 'integer',
                'data.priority_id' => 'integer',
                'data.start_date' => 'string',
                'data.end_date' => 'string',
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.description',
                'data.created_by',
                'data.assigned_to',
                'data.status_id',
                'data.priority_id',
                'data.start_date',
                'data.end_date'
            ]);

            $json->whereAll([
                'data.id' => $task[0]->id,
                'data.name' => $task[0]->name,
                'data.description' => $task[0]->description,
                'data.created_by' => $task[0]->created_by,
                'data.assigned_to' => $task[0]->assigned_to,
                'data.status_id' => $task[0]->status_id,
                'data.priority_id' => $task[0]->priority_id,
                'data.start_date' => $task[0]->start_date,
                'data.end_date' => $task[0]->end_date
            ]);

        });
    }

    /**
     * Teste de criação de tarefa.
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
        $userCreatedBy = User::factory()->for($category)->create();
        $userAssignedTo = User::factory()->for($category)->create();
        $status = Status::factory()->create();
        $priority = Priority::factory()->create();
        $project = Project::factory()->for($status)->create();

        $payload['created_by'] = $userCreatedBy->id;
        $payload['assigned_to'] = $userAssignedTo->id;
        $payload['status_id'] = $status->id;
        $payload['priority_id'] = $priority->id;
        $payload['project_id'] = $project->id;

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
        $userCreatedBy = User::factory()->for($category)->create();
        $userAssignedTo = User::factory()->for($category)->create();
        $status = Status::factory()->create();
        $priority = Priority::factory()->create();
        $project = Project::factory()->for($status)->create();

        $payload['created_by'] = $userCreatedBy->id;
        $payload['assigned_to'] = $userAssignedTo->id;
        $payload['status_id'] = $status->id;
        $payload['priority_id'] = $priority->id;
        $payload['project_id'] = $project->id;

        $task = Task::factory()->for($status)->for($priority)->for($project)->create([
            'created_by' => $userCreatedBy->id,
            'assigned_to' => $userAssignedTo->id
        ]);

        $response = $this->putJson("{$this->endpoint}/{$task->id}", $payload,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus($code);
        $response->assertJsonStructure($structure);

        $response->assertJson(function (AssertableJson $json) use($payload, $jsonWhere) {

            $json->whereAll($jsonWhere);

        });
    }

    /**
     * Teste de exclusão de tarefa.
     *
     * @return void
     */
    public function test_delete(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $userCreatedBy = User::factory()->for($category)->create();
        $userAssignedTo = User::factory()->for($category)->create();
        $status = Status::factory()->create();
        $priority = Priority::factory()->create();
        $project = Project::factory()->for($status)->create();

        $task = Task::factory()->for($status)->for($priority)->for($project)->create([
            'created_by' => $userCreatedBy->id,
            'assigned_to' => $userAssignedTo->id
        ]);

        $response = $this->deleteJson("{$this->endpoint}/{$task->id}",[],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(204);
        $response->assertNoContent();
    }

    /**
     * Data provider de criação de tarefa.
     *
     * @return array[]
     */
    protected function dataProviderCreate(): array
    {
        return [
            'creating with all data' => [
                'payload' => [
                    'name' => 'Testando',
                    'description' => 'Testando descricao',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d'),
                ],
                'code' => 201,
                'structure' => [
                    'data' => ['name', 'description', 'start_date', 'end_date', 'created_by', 'assigned_to',
                        'status_id', 'priority_id']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando',
                    'data.description' => 'Testando descricao',
                    'data.created_by' => 29,
                    'data.assigned_to' => 30,
                    'data.status_id' => 12,
                    'data.priority_id' => 3,
                    'data.start_date' => date('Y-m-d'),
                    'data.end_date' => date('Y-m-d')
                ]
            ],
            'creating without name' => [
                'payload' => [
                    'description' => 'Testando descricao',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d'),
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The name field is required.',
                    'errors' => ['name' => ['The name field is required.']]
                ]
            ]
        ];
    }

    /**
     * Data provider de atualização de tarefa.
     *
     * @return array[]
     */
    protected function dataProviderUpdate(): array
    {
        return [
            'updating with all data' => [
                'payload' => [
                    'name' => 'Testando',
                    'description' => 'Testando descricao',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d'),
                ],
                'code' => 200,
                'structure' => [
                    'data' => ['name', 'description', 'start_date', 'end_date', 'created_by', 'assigned_to',
                        'status_id', 'priority_id']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando',
                    'data.description' => 'Testando descricao',
                    'data.created_by' => 35,
                    'data.assigned_to' => 36,
                    'data.status_id' => 14,
                    'data.priority_id' => 5,
                    'data.start_date' => date('Y-m-d'),
                    'data.end_date' => date('Y-m-d')
                ]
            ],
            'creating without name' => [
                'payload' => [
                    'description' => 'Testando descricao',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d'),
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The name field is required.',
                    'errors' => ['name' => ['The name field is required.']]
                ]
            ]
        ];
    }
}
