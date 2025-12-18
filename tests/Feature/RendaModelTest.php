<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Renda;

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

        $renda = Renda::create([
            'user_id' => $user->id,
            'saldo' => 100,
            'custo' => 0,
        ]);

        $this->assertDatabaseHas('rendas', [
            'user_id' => $user->id,
            'saldo' => 100,
            'custo' => 0,
        ]);
    }
}
