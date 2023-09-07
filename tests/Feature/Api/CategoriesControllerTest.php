<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoriesControllerTest extends TestCase
{
    protected string $endpoint = '/categories';

    /**
     * Testando a listagem de todas as categorias.
     *
     * @return void
     */
    public function test_get_all(): void
    {
        $categoires = Category::factory(3)->create();

        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJson(function (AssertableJson $json) use($categoires) {
            $json->whereAllType([
                'data.0.id' => 'integer',
                'data.0.name' => 'string',
                'data.0.color' => 'string',
                'data.0.active' => 'integer',
            ]);

            $json->hasAll([
                'data.0.id',
                'data.0.name',
                'data.0.color',
                'data.0.active',
            ]);

            $category = $categoires->first();

            $json->whereAll([
                'data.0.id' => $category->id,
                'data.0.name' => $category->name,
                'data.0.color' => $category->color,
                'data.0.active' => $category->active,
            ]);

        });
    }

    /**
     * Testando a listagem de 1 categoria especifica.
     *
     * @return void
     */
    public function test_find_single(): void
    {
        $category = Category::factory(1)->create();

        $response = $this->getJson("{$this->endpoint}/{$category[0]->id}");
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'color',
                'active'
            ]
        ]);

        $response->assertJson(function (AssertableJson $json) use($category) {
            $json->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.color' => 'string',
                'data.active' => 'integer',
            ]);

            $json->hasAll([
                'data.id',
                'data.name',
                'data.color',
                'data.active',
            ]);

            $json->whereAll([
                'data.id' => $category[0]->id,
                'data.name' => $category[0]->name,
                'data.color' => $category[0]->color,
                'data.active' => $category[0]->active,
            ]);

        });
    }

    /**
     * Teste de criação de categoria
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
        $response = $this->postJson($this->endpoint, $payload);
        $response->assertStatus($code);
        $response->assertJsonStructure($structure);

        $response->assertJson(function (AssertableJson $json) use($payload, $jsonWhere) {

            $json->whereAll($jsonWhere);

        });
    }

    /**
     * Teste de atualização de categoria
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
        $category = Category::factory()->create();
        $response = $this->putJson("{$this->endpoint}/{$category->id}", $payload);
        $response->assertStatus($code);
        $response->assertJsonStructure($structure);

        $response->assertJson(function (AssertableJson $json) use($payload, $jsonWhere) {

            $json->whereAll($jsonWhere);

        });
    }

    public function test_delete()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson("{$this->endpoint}/{$category->id}");
        $response->assertStatus(204);
        $response->assertNoContent();
    }

    /**
     * Data provider de criação de categoria.
     *
     * @return array[]
     */
    protected function dataProviderCreate(): array
    {
        return [
              'creating with all data' => [
                  'payload' => [
                      'name' => 'Testando',
                      'color' => 'Testando cor',
                      'active' => 1
                  ],
                  'code' => 201,
                  'structure' => [
                      'data' => ['name', 'color', 'active']
                  ],
                  'jsonWhere' => [
                      'data.name' => 'Testando',
                      'data.color' => 'Testando cor'
                  ]
              ],
              'creating without active' => [
                  'payload' => [
                      'name' => 'Testando',
                      'color' => 'Testando cor',
                  ],
                  'code' => 422,
                  'structure' => [
                      'message',
                      'errors'
                  ],
                  'jsonWhere' => [
                      'message' => 'The active field is required.',
                      'errors' => ['active' => ['The active field is required.']]
                  ]
              ]
        ];
    }

    /**
     * Data provider de atualização de categoria.
     *
     * @return array[]
     */
    protected function dataProviderUpdate(): array
    {
        return [
            'updating with all data' => [
                'payload' => [
                    'name' => 'Testando',
                    'color' => 'Testando cor',
                    'active' => 1
                ],
                'code' => 200,
                'structure' => [
                    'data' => ['name', 'color', 'active']
                ],
                'jsonWhere' => [
                    'data.name' => 'Testando',
                    'data.color' => 'Testando cor'
                ]
            ],
            'updating without active' => [
                'payload' => [
                    'name' => 'Testando',
                    'color' => 'Testando cor',
                ],
                'code' => 422,
                'structure' => [
                    'message',
                    'errors'
                ],
                'jsonWhere' => [
                    'message' => 'The active field is required.',
                    'errors' => ['active' => ['The active field is required.']]
                ]
            ]
        ];
    }
}
