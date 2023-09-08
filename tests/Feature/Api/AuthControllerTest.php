<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use UtilsTrait;

    /**
     * Teste de autenticação sem dados do formulário
     *
     * @return void
     */
    public function test_fail_auth(): void
    {
        $response = $this->postJson('/login',[]);

        $response->assertStatus(422);
    }

    /**
     * Testes de autenticação com sucesso.
     *
     * @return void
     */
    public function test_auth()
    {
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();

        $response = $this->postJson('/login',[
            'email' => $user->email,
            'password' => 'teste',
            'device_name' => 'web'
        ]);

        $response->assertStatus(200);
    }

    /**
     * Teste de logout sem token de autenticação.
     *
     * @return void
     */
    public function test_fail_logout()
    {
        $response = $this->postJson('/logout');

        $response->assertStatus(401);
    }

    /**
     * Teste de logout com token de autenticação.
     *
     * @return void
     */
    public function test_logout()
    {
        $token = $this->createToken();

        $response = $this->postJson('/logout', [], [
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
    }

    /**
     * Teste de requisição na rota me com token de autenticação.
     *
     * @return void
     */
    public function test_get_me()
    {
        $token = $this->createToken();

        $response = $this->postJson('/me', [], [
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertStatus(200);
    }
}
