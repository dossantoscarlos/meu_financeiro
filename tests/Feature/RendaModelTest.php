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

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Teste',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($this->user);
    }

    /**
     * Teste de criação de renda.
     */
    public function test_create_renda(): void
    {

        $saldo = 100;
        $custo = 0;

        $data = [
            'user_id' => $this->user->id,
            'saldo' => $saldo,
            'custo' => $custo,
        ];

        Renda::create($data);

        $this->assertDatabaseHas('rendas', $data);
    }
}
