<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\TipoDespesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TipoDespesaModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de criação de tipo de despesa.
     */
    public function test_create_tipo_despesa(): void
    {
        TipoDespesa::create([
            'nome' => mb_strtoupper('Teste'),
        ]);

        $this->assertDatabaseHas('tipo_despesas', [
            'nome' => mb_strtoupper('Teste'),
        ]);
    }
}
