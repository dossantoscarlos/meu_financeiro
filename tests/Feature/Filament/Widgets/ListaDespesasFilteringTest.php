<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Widgets\ListaDespesasWidget;
use App\Models\Despesa;
use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class ListaDespesasFilteringTest extends TestCase
{
    public function test_it_filters_despesas_correctly(): void
    {
        // Setup dates (mocking time not needed if we set mes_ano correctly)
        $now = Carbon::now();
        $currentMesAno = $now->format('m/Y');
        $pastMesAno = $now->copy()->subMonth()->format('m/Y');
        $futureMesAno = $now->copy()->addMonth()->format('m/Y');

        $user = User::factory()->create();

        // Create Statuses
        $statusPago = StatusDespesa::create(['nome' => 'pago']);
        $statusPendente = StatusDespesa::create(['nome' => 'pendente']);
        $statusAtrasado = StatusDespesa::create(['nome' => 'atrasado']);

        // Create Plans
        $currentPlano = Plano::factory()->create(['user_id' => $user->id, 'mes_ano' => $currentMesAno]);
        $pastPlano = Plano::factory()->create(['user_id' => $user->id, 'mes_ano' => $pastMesAno]);
        $futurePlano = Plano::factory()->create(['user_id' => $user->id, 'mes_ano' => $futureMesAno]);

        // Current Plan Despesas (Should show all)
        // Only 1 to save space
        $dCurrentPendente = Despesa::factory()->create(['plano_id' => $currentPlano->id, 'status_despesa_id' => $statusPendente->id, 'descricao' => 'Current Pendente']);

        // Past Plan Despesas (Should show only Pendente/Atrasado)
        $dPastPago = Despesa::factory()->create(['plano_id' => $pastPlano->id, 'status_despesa_id' => $statusPago->id, 'descricao' => 'Past Pago']);
        $dPastPendente = Despesa::factory()->create(['plano_id' => $pastPlano->id, 'status_despesa_id' => $statusPendente->id, 'descricao' => 'Past Pendente']);
        // Removed Past Atrasado to keep count low (Total visible: 2)

        // Future Plan Despesas (Should NOT show)
        $dFuturePago = Despesa::factory()->create(['plano_id' => $futurePlano->id, 'status_despesa_id' => $statusPago->id, 'descricao' => 'Future Pago']);

        Livewire::actingAs($user)
            ->test(ListaDespesasWidget::class)
            ->assertSee('Current Pendente')
            ->assertSee('Past Pendente')
            ->assertDontSee('Past Pago')
            ->assertDontSee('Future Pago');
    }
}
