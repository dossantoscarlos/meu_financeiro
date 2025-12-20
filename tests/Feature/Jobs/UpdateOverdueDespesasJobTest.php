<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Jobs\UpdateOverdueDespesasJob;
use App\Models\Despesa;
use App\Models\StatusDespesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateOverdueDespesasJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_statuses_correctly(): void
    {
        // Seed statuses
        $pendente = StatusDespesa::factory()->create(['nome' => 'pendente']);
        $atrasado = StatusDespesa::factory()->create(['nome' => 'atrasado']);
        $pago = StatusDespesa::factory()->create(['nome' => 'pago']);

        // 1. Overdue & Pendente -> should become Atrasado
        $overduePendente = Despesa::factory()->create([
            'status_despesa_id' => $pendente->id,
            'data_vencimento' => Carbon::yesterday()->toDateString(),
        ]);

        // 2. Overdue & Pago -> should stay Pago
        $overduePago = Despesa::factory()->create([
            'status_despesa_id' => $pago->id,
            'data_vencimento' => Carbon::yesterday()->toDateString(),
        ]);

        // 3. Future & Atrasado -> should become Pendente
        $futureAtrasado = Despesa::factory()->create([
            'status_despesa_id' => $atrasado->id,
            'data_vencimento' => Carbon::tomorrow()->toDateString(),
        ]);

        // 4. Future & Pago -> should stay Pago
        $futurePago = Despesa::factory()->create([
            'status_despesa_id' => $pago->id,
            'data_vencimento' => Carbon::tomorrow()->toDateString(),
        ]);

        // Run the job
        (new UpdateOverdueDespesasJob())->handle();

        // Asserts
        $overduePendente->refresh();
        $this->assertEquals($atrasado->id, $overduePendente->status_despesa_id);

        $overduePago->refresh();
        $this->assertEquals($pago->id, $overduePago->status_despesa_id);

        $futureAtrasado->refresh();
        $this->assertEquals($pendente->id, $futureAtrasado->status_despesa_id);

        $futurePago->refresh();
        $this->assertEquals($pago->id, $futurePago->status_despesa_id);
    }
}
