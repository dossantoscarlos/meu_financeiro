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
        $pendente = StatusDespesa::factory()->create(['nome' => 'pendente']);
        $atrasado = StatusDespesa::factory()->create(['nome' => 'atrasado']);
        $paid = StatusDespesa::factory()->create(['nome' => 'pago']);

        $overduePendente = Despesa::factory()->create([
            'status_despesa_id' => $pendente->id,
            'data_vencimento' => Carbon::yesterday()->toDateString(),
        ]);

        $overduePaid = Despesa::factory()->create([
            'status_despesa_id' => $paid->id,
            'data_vencimento' => Carbon::yesterday()->toDateString(),
        ]);


        (new UpdateOverdueDespesasJob())->handle();

        $overduePendente->refresh();
        $this->assertEquals($atrasado->id, $overduePendente->status_despesa_id);

        $overduePaid->refresh();
        $this->assertEquals($paid->id, $overduePaid->status_despesa_id);
    }
}
