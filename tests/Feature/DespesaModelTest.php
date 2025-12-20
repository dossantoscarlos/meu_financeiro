<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Despesa;
use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\TipoDespesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DespesaModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de criação de despesa.
     */
    public function test_create_despesa(): void
    {

        $user = User::create([
            'name' => 'Teste',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $tipo_despesa = TipoDespesa::create([
            'nome' => 'Teste',
        ]);

        $status_despesa = StatusDespesa::create([
            'nome' => 'Teste',
        ]);

        $plano = Plano::create([
            'descricao_simples' => 'Teste',
            'mes_ano' => '2025-12',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        Despesa::create([
            'descricao' => 'Teste',
            'valor_documento' => 100,
            'data_vencimento' => '2025-12-17',
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipo_despesa->id,
            'status_despesa_id' => $status_despesa->id,
        ]);

        $this->assertDatabaseHas('despesas', [
            'descricao' => 'Teste',
            'valor_documento' => 100,
            'data_vencimento' => '2025-12-17',
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipo_despesa->id,
            'status_despesa_id' => $status_despesa->id,
        ]);
    }
}
