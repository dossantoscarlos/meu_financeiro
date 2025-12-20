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
use Illuminate\Support\Facades\Log;

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

        if (!$statusPendente || !$statusAtrasado || !$statusPago) {
            Log::warning('UpdateOverdueDespesasJob: Required statuses (pendente, atrasado, or pago) not found.');
            return;
        }

        $today = now()->toDateString();

        // 1. Update to 'atrasado': not paid and vencimento < today
        $atrasadoCount = Despesa::where('status_despesa_id', '!=', $statusPago->id)
            ->where('data_vencimento', '<', $today)
            ->where('status_despesa_id', '!=', $statusAtrasado->id)
            ->update(['status_despesa_id' => $statusAtrasado->id]);

        // 2. Update to 'pendente': not paid and vencimento >= today
        $pendenteCount = Despesa::where('status_despesa_id', '!=', $statusPago->id)
            ->where('data_vencimento', '>=', $today)
            ->where('status_despesa_id', '!=', $statusPendente->id)
            ->update(['status_despesa_id' => $statusPendente->id]);

        if ($atrasadoCount > 0 || $pendenteCount > 0) {
            Log::info("UpdateOverdueDespesasJob: Updated {$atrasadoCount} to 'atrasado' and {$pendenteCount} to 'pendente'.");
        }
    }
}
