<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Produto;
use App\Models\User;
use Tests\TestCase;

class ProdutoModelTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * A basic feature test example.
     */
    public function test_create_produto(): void
    {

        Produto::create([
            'descricao_curta' => 'Teste',
            'preco' => 100,
            'quantidade' => 1,
            'tipo_medida' => 'unidade',
            'total' => 100,
            'data_compra' => now()->format('Y-m-d'),
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('produtos', [
            'descricao_curta' => 'Teste',
            'preco' => 100,
            'quantidade' => 1,
            'tipo_medida' => 'unidade',
            'total' => 100,
            'data_compra' => now()->format('Y-m-d'),
            'user_id' => $this->user->id,
        ]);
    }
}
