<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Despesa;
use App\Models\HistoricoDespesa;
use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\TipoDespesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoricoDespesaModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste de criação de histórico de despesa.
     */
    public function test_create_historico_despesa(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $statusDespesa = StatusDespesa::create(['nome' => 'Pendente']);
        $tipoDespesa = TipoDespesa::create(['nome' => 'Alimentação']);

        $plano = Plano::factory()->create([
            'user_id' => $user->id,
        ]);

        $despesa = Despesa::create([
            'descricao' => 'Teste Despesa',
            'valor_documento' => 100.00,
            'data_vencimento' => now()->format('Y-m-d'),
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipoDespesa->id,
            'status_despesa_id' => $statusDespesa->id,
        ]);

        $historico = HistoricoDespesa::create([
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $statusDespesa->id,
            'data' => now()->format('Y-m-d'),
        ]);

        $this->assertDatabaseHas('historico_despesas', [
            'id' => $historico->id,
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $statusDespesa->id,
        ]);
    }

    /**
     * Teste de relacionamento com despesa.
     */
    public function test_historico_belongs_to_despesa(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $statusDespesa = StatusDespesa::create(['nome' => 'Pendente']);
        $tipoDespesa = TipoDespesa::create(['nome' => 'Alimentação']);

        $plano = Plano::factory()->create([
            'user_id' => $user->id,
        ]);

        $despesa = Despesa::create([
            'descricao' => 'Teste Despesa',
            'valor_documento' => 100.00,
            'data_vencimento' => now()->format('Y-m-d'),
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipoDespesa->id,
            'status_despesa_id' => $statusDespesa->id,
        ]);

        $historico = HistoricoDespesa::create([
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $statusDespesa->id,
            'data' => now()->format('Y-m-d'),
        ]);

        $this->assertInstanceOf(Despesa::class, $historico->despesa);
        $this->assertEquals($despesa->id, $historico->despesa->id);
    }

    /**
     * Teste de relacionamento com status despesa.
     */
    public function test_historico_belongs_to_status_despesa(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $statusDespesa = StatusDespesa::create(['nome' => 'Pendente']);
        $tipoDespesa = TipoDespesa::create(['nome' => 'Alimentação']);

        $plano = Plano::factory()->create([
            'user_id' => $user->id,
        ]);

        $despesa = Despesa::create([
            'descricao' => 'Teste Despesa',
            'valor_documento' => 100.00,
            'data_vencimento' => now()->format('Y-m-d'),
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipoDespesa->id,
            'status_despesa_id' => $statusDespesa->id,
        ]);

        $historico = HistoricoDespesa::create([
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $statusDespesa->id,
            'data' => now()->format('Y-m-d'),
        ]);

        $this->assertInstanceOf(StatusDespesa::class, $historico->status_despesa);
        $this->assertEquals($statusDespesa->id, $historico->status_despesa->id);
    }
}
