<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Models\StatusDespesa;
use App\Jobs\UpdateOverdueDespesasJob;
use App\Models\Despesa;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOverdueDespesasJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_statuses_correctly(): void
    {
        foreach ([1 => 'pendente', 2 => 'atrasado', 3 => 'pago'] as $id => $nome) {
            StatusDespesa::updateOrCreate(
                ['id' => $id],
                ['nome' => $nome]
            );
        }

        $overduePendente = Despesa::factory()->create([
            'status_despesa_id' => StatusDespesa::PENDENTE,
            'data_vencimento' => \Illuminate\Support\Facades\Date::yesterday()->toDateString(),
        ]);

        $overduePaid = Despesa::factory()->create([
            'status_despesa_id' => StatusDespesa::PAGO,
            'data_vencimento' => \Illuminate\Support\Facades\Date::yesterday()->toDateString(),
        ]);

        (new UpdateOverdueDespesasJob())->handle();

        $overduePendente->refresh();
        $this->assertEquals(StatusDespesa::ATRASADO, $overduePendente->status_despesa_id);

        $overduePaid->refresh();
        $this->assertEquals(StatusDespesa::PAGO, $overduePaid->status_despesa_id);
    }
}
