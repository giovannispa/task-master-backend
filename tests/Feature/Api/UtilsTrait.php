<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;

/**
 * Trait UtilsTrait
 *
 * Trait criada para geração automática de tokens de usuário para testar endpoints que necesitam de autenticação.
 */
trait UtilsTrait
{
    /**
     * Método que retorna um token de usuário logado para ser utilizado nos testes.
     *
     * @return string
     */
    public function createToken()
    {
        $category = Category::factory()->create();
        $user = User::factory()->for($category)->create();
        $token = $user->createToken('web')->plainTextToken;

        return $token;
    }
}
