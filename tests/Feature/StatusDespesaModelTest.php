<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\StatusDespesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusDespesaModelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Teste de criação de status de despesa.
     */
    public function test_create_status_despesa(): void
    {
        StatusDespesa::create([
            'nome' => 'Teste',
        ]);

        $this->assertDatabaseHas('status_despesas', [
            'nome' => 'Teste',
        ]);
    }
}
