<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Renda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendaModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de criação de renda.
     */
    public function test_create_renda(): void
    {
        $user = User::create([
            'name' => 'Teste',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user);
        $saldo = 100;
        $custo = 0;

        $data = [
            'user_id' => $user->id,
            'saldo' => $saldo,
            'custo' => $custo,
        ];

        $renda = Renda::create($data);

        $this->assertDatabaseHas('rendas', $data);
    }
}
