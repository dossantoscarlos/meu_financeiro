<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Produto;
use App\Models\User;
use Tests\TestCase;

class ProdutoModelTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_produto(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $produto = Produto::create([
            'descricao_curta' => 'Teste',
            'preco' => 100,
            'quantidade' => 1,
            'tipo_medida' => 'unidade',
            'total' => 100,
            'data_compra' => now()->format('Y-m-d'),
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('produtos', [
            'descricao_curta' => 'Teste',
            'preco' => 100,
            'quantidade' => 1,
            'tipo_medida' => 'unidade',
            'total' => 100,
            'data_compra' => now()->format('Y-m-d'),
            'user_id' => $user->id,
        ]);
    }
}
