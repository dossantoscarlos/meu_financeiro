<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Plano;
use App\Models\User;
use Tests\TestCase;

class PlanoModelTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_plano(): void
    {

        $user = User::factory()->create();

        $this->actingAs($user);

        $plano = Plano::create([
            'descricao_simples' => 'Teste',
            'mes_ano' => '10/2025',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('planos', [
            'descricao_simples' => 'Teste',
            'mes_ano' => '10/2025',
            'user_id' => $user->id,
        ]);
    }
}
