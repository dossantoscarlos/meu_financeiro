<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Despesa;
use App\Models\HistoricoDespesa;

trait HistoricoStatusDespesa
{
    private function registerHistoricoStatusDespesa(Despesa $despesa): void
    {
        $historico = HistoricoDespesa::query()
            ->where('despesa_id', '=', $despesa->id)
            ->where('status_despesa_id', '=', $despesa->status_despesa_id)
            ->first();

        if ($historico) {
            return;
        }

        HistoricoDespesa::create([
            'despesa_id' => $despesa->id,
            'status_despesa_id' => $despesa->status_despesa_id,
            'data' => now()->format('Y-m-d'),
        ]);
    }
}
