<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Plano;
use App\Models\User;
use Tests\TestCase;

class PlanoModelTest extends TestCase
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
    public function test_create_plano(): void
    {

        Plano::create([
            'descricao_simples' => 'Teste',
            'mes_ano' => '10/2025',
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('planos', [
            'descricao_simples' => 'Teste',
            'mes_ano' => '10/2025',
            'user_id' => $this->user->id,
        ]);
    }
}
