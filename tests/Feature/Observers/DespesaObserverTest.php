<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Models\Despesa;
use App\Models\HistoricoDespesa;
use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\TipoDespesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DespesaObserverTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste que verifica se o histórico é criado quando uma despesa é criada.
     */
    public function test_historico_is_created_when_despesa_is_created(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $statusPendente = StatusDespesa::create(['nome' => 'Pendente']);
        $tipoDespesa = TipoDespesa::create(['nome' => 'Alimentação']);

        $plano = Plano::factory()->create([
            'user_id' => $user->id,
        ]);

        // Verifica que não há histórico antes
        $this->assertEquals(0, HistoricoDespesa::count());

        // Cria a despesa
        $despesa = Despesa::create([
            'descricao' => 'Teste Despesa',
            'valor_documento' => 100.00,
            'data_vencimento' => now()->format('Y-m-d'),
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipoDespesa->id,
            'status_despesa_id' => $statusPendente->id,
        ]);

        // Verifica que o histórico foi criado
        $this->assertEquals(1, HistoricoDespesa::count());

        $this->assertDatabaseHas('historico_despesas', [
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $statusPendente->id,
        ]);
    }

    /**
     * Teste que verifica se cada mudança de status cria uma nova linha no histórico.
     */
    public function test_each_status_change_creates_new_historico_row(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $statusPendente = StatusDespesa::create(['nome' => 'Pendente']);
        $statusPago = StatusDespesa::create(['nome' => 'Pago']);
        $statusAtrasado = StatusDespesa::create(['nome' => 'Atrasado']);
        $tipoDespesa = TipoDespesa::create(['nome' => 'Alimentação']);

        $plano = Plano::factory()->create([
            'user_id' => $user->id,
        ]);

        // Cria a despesa com status Pendente
        $despesa = Despesa::create([
            'descricao' => 'Teste Despesa',
            'valor_documento' => 100.00,
            'data_vencimento' => now()->format('Y-m-d'),
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipoDespesa->id,
            'status_despesa_id' => $statusPendente->id,
        ]);

        // Deve ter 1 registro no histórico (criação)
        $this->assertEquals(1, HistoricoDespesa::where('despesa_id', $despesa->id)->count());

        $this->assertDatabaseHas('historico_despesas', [
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $statusPendente->id,
        ]);

        // Atualiza para status Pago
        $despesa->update(['status_despesa_id' => $statusPago->id]);

        // Deve ter 2 registros no histórico
        $this->assertEquals(2, HistoricoDespesa::where('despesa_id', $despesa->id)->count());

        $this->assertDatabaseHas('historico_despesas', [
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $statusPago->id,
        ]);

        // Atualiza para status Atrasado
        $despesa->update(['status_despesa_id' => $statusAtrasado->id]);

        // Deve ter 3 registros no histórico
        $this->assertEquals(3, HistoricoDespesa::where('despesa_id', $despesa->id)->count());

        $this->assertDatabaseHas('historico_despesas', [
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $statusAtrasado->id,
        ]);

        // Verifica que todos os 3 status estão no histórico
        $historicos = HistoricoDespesa::where('despesa_id', $despesa->id)
            ->orderBy('created_at')
            ->get();

        $this->assertEquals($statusPendente->id, $historicos[0]->status_despesa_id);
        $this->assertEquals($statusPago->id, $historicos[1]->status_despesa_id);
        $this->assertEquals($statusAtrasado->id, $historicos[2]->status_despesa_id);
    }

    /**
     * Teste que verifica se o histórico é criado mesmo quando outros campos são atualizados.
     */
    public function test_historico_is_created_when_status_remains_same_but_other_fields_change(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $statusPendente = StatusDespesa::create(['nome' => 'Pendente']);
        $statusPago = StatusDespesa::create(['nome' => 'Pago']);
        $tipoDespesa = TipoDespesa::create(['nome' => 'Alimentação']);

        $plano = Plano::factory()->create([
            'user_id' => $user->id,
        ]);

        // Cria a despesa
        $despesa = Despesa::create([
            'descricao' => 'Teste Despesa',
            'valor_documento' => 100.00,
            'data_vencimento' => now()->format('Y-m-d'),
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipoDespesa->id,
            'status_despesa_id' => $statusPendente->id,
        ]);

        // 1 registro inicial
        $this->assertEquals(1, HistoricoDespesa::where('despesa_id', $despesa->id)->count());

        // Atualiza apenas o valor, mantendo o mesmo status
        $despesa->update(['valor_documento' => 200.00]);

        // Deve manter 1 registro (o observer registra se o status mudar)
        $this->assertEquals(1, HistoricoDespesa::where('despesa_id', $despesa->id)
            ->where('status_despesa_id', $statusPendente->id)
            ->count());

        // Atualiza o status
        $despesa->update(['status_despesa_id' => $statusPago->id]);

        // Deve ter 1 registro (o observer registra se o status mudar)
        $this->assertEquals(1, HistoricoDespesa::where('despesa_id', $despesa->id)
            ->where('status_despesa_id', $statusPendente->id)
            ->count());

        // Deve ter 2 registros (o observer registra se o status mudar)
        $this->assertEquals(2, HistoricoDespesa::where('despesa_id', $despesa->id)
            ->count());
    }

    /**
     * Teste que verifica se múltiplas despesas têm históricos independentes.
     */
    public function test_multiple_despesas_have_independent_historicos(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $statusPendente = StatusDespesa::create(['nome' => 'Pendente']);
        $statusPago = StatusDespesa::create(['nome' => 'Pago']);
        $tipoDespesa = TipoDespesa::create(['nome' => 'Alimentação']);

        $plano = Plano::factory()->create([
            'user_id' => $user->id,
        ]);

        // Cria primeira despesa
        $despesa1 = Despesa::create([
            'descricao' => 'Despesa 1',
            'valor_documento' => 100.00,
            'data_vencimento' => now()->format('Y-m-d'),
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipoDespesa->id,
            'status_despesa_id' => $statusPendente->id,
        ]);

        // Cria segunda despesa
        $despesa2 = Despesa::create([
            'descricao' => 'Despesa 2',
            'valor_documento' => 200.00,
            'data_vencimento' => now()->format('Y-m-d'),
            'plano_id' => $plano->id,
            'tipo_despesa_id' => $tipoDespesa->id,
            'status_despesa_id' => $statusPendente->id,
        ]);

        // Cada despesa deve ter 1 registro
        $this->assertEquals(1, HistoricoDespesa::where('despesa_id', $despesa1->id)->count());
        $this->assertEquals(1, HistoricoDespesa::where('despesa_id', $despesa2->id)->count());

        // Atualiza apenas a primeira despesa
        $despesa1->update(['status_despesa_id' => $statusPago->id]);

        // Primeira despesa deve ter 2 registros, segunda ainda 1
        $this->assertEquals(2, HistoricoDespesa::where('despesa_id', $despesa1->id)->count());
        $this->assertEquals(1, HistoricoDespesa::where('despesa_id', $despesa2->id)->count());
    }
}
