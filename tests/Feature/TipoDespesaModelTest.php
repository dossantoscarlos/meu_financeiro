<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TipoDespesa;

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

