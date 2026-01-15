<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\HistoricoDespesa;

trait HistoricoStatusDespesa
{
    private function registerHistoricoStatusDespesa($despesa): void
    {
        $historico = HistoricoDespesa::whereDespesaId($despesa->id)->whereStatusDespesaId($despesa->status_despesa_id)->first();

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
