<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Despesa;
use App\Models\StatusDespesa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateOverdueDespesasJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $statusPendente = StatusDespesa::where('nome', 'pendente')->first();
        $statusAtrasado = StatusDespesa::where('nome', 'atrasado')->first();
        $statusPago = StatusDespesa::where('nome', 'pago')->first();

        $today = now()->toDateString();

        // 1. Update to 'atrasado': not paid and vencimento < today
        Despesa::where('status_despesa_id', '!=', $statusPago->id)
            ->where('data_vencimento', '<', $today)
            ->where('status_despesa_id', '!=', $statusAtrasado->id)
            ->update(['status_despesa_id' => $statusAtrasado->id]);
    }
}
