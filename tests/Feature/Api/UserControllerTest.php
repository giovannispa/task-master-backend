<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use UtilsTrait;

    /**
     * @var string
     */
    protected string $endpoint = '/users';

    /**
     * Testando a listagem de todos os usuários.
     *
     * @return void
     */
    public function test_get_all(): void
    {
        $category = Category::factory()->create();
        $users = User::factory(2)->for($category)->create();
        $token = $this->createToken();

        $response = $this->getJson($this->endpoint,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');

        $response->assertJson(function (AssertableJson $json) use($users) {
            $json->whereAllType([
                'data.0.id' => 'integer',
                'data.0.name' => 'string',
                'data.0.email' => 'string',
                'data.0.category_id' => 'integer',
                'data.0.active' => 'integer',
                'data.0.worked_projects' => 'integer',
                'data.0.tasks_performed' => 'integer',
            ]);

            $json->hasAll([
                'data.0.id',
                'data.0.name',
                'data.0.email',
                'data.0.category_id',
                'data.0.active',
                'data.0.worked_projects',
                'data.0.tasks_performed'
            ]);

            $user = $users->first();

            $json->whereAll([
                'data.0.id' => $user->id,
                'data.0.name' => $user->name,
                'data.0.email' => $user->email,
                'data.0.category_id' => $user->category_id,
                'data.0.active' => $user->active,
                'data.0.worked_projects' => $user->worked_projects,
                'data.0.tasks_performed' => $user->tasks_performed
            ]);

        });
    }

    /**
     * Testando a listagem de 1 usuário especifico.
     *
     * @return void
     */
    public function test_find_single(): void
    {
        $category = Category::factory()->create();
        $user = User::factory(1)->for($category)->create();
        $token = $this->createToken();

        $response = $this->getJson("{$this->endpoint}/{$user[0]->id}",[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'category_id',
                'active',
                'worked_projects',
                'tasks_performed',
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($user) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.email' => 'string',
                'data.category_id' => 'integer',
                'data.active' => 'integer',
                'data.worked_projects' => 'integer',
                'data.tasks_performed' => 'integer',
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.email',
                'data.category_id',
                'data.active',
                'data.worked_projects',
                'data.tasks_performed'
            ]);

            $json->whereAll([
                'data.id' => $user[0]->id,
                'data.name' => $user[0]->name,
                'data.email' => $user[0]->email,
                'data.category_id' => $user[0]->category_id,
                'data.active' => $user[0]->active,
                'data.worked_projects' => $user[0]->worked_projects,
                'data.tasks_performed' => $user[0]->tasks_performed
            ]);

        });
    }

    /**
     * Teste de criação de usuário.
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

        $payload['category_id'] = $category->id;

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
     * Teste de atualização de usuário.
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

        $payload['category_id'] = $category->id;

        $response = $this->putJson("{$this->endpoint}/{$user->id}", $payload,[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus($code);
        $response->assertJsonStructure($structure);

        $response->assertJson(function (AssertableJson $json) use($payload, $jsonWhere) {

            $json->whereAll($jsonWhere);

        });
    }

    /**
     * Teste de exclusão de usuário.
     *
     * @return void
     */
    public function test_delete(): void
    {
        $token = $this->createToken();
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();

        $response = $this->deleteJson("{$this->endpoint}/{$user->id}",[],[
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(204);
        $response->assertNoContent();
    }

    /**
     * Data provider de criação de usuário.
     *
     * @return array[]
     */
    protected function dataProviderCreate(): array
    {
        return [
            'creating with all data' => [
                'payload' => [
                    'name' => 'Testando cadastro',
                    'email' => 'testando@gmail.com',
                    'password' => 'testando',
                    'password_confirmation' => 'testando',
                ],
                'code' => 201,
                'structure' => [
                    'data' => ['id', 'name', 'email', 'category_id']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando cadastro',
                    'data.email' => 'testando@gmail.com',
                ]
            ],
            'creating without email' => [
                'payload' => [
                    'name' => 'Testando',
                    'password' => 'testando',
                    'password_confirmation' => 'testando',
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The email field is required.',
                    'errors' => ['email' => ['The email field is required.']]
                ]
            ]
        ];
    }

    /**
     * Data provider de atualização de usuário.
     *
     * @return array[]
     */
    protected function dataProviderUpdate(): array
    {
        return [
            'updating with all data' => [
                'payload' => [
                    'name' => 'Testando atualizacao',
                    'email' => 'testando@gmail.com',
                ],
                'code' => 200,
                'structure' => [
                    'data' => ['name', 'email', 'category_id']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando atualizacao',
                    'data.email' => 'testando@gmail.com'
                ]
            ],
            'updating without email' => [
                'payload' => [
                    'name' => 'Testando atualizacao',
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The email field is required.',
                    'errors' => ['email' => ['The email field is required.']]
                ]
            ]
        ];
    }
}
