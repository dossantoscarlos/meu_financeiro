<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\StatusDespesa;


class StatusDespesaModelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Teste de criação de status de despesa.
     */
    public function test_create_status_despesa(): void
    {
        $response = StatusDespesa::create([
            'nome' => 'Teste',
        ]);

        $this->assertDatabaseHas('status_despesas', [
            'nome' => 'Teste',
        ]);
    }
}
