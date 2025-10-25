<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Despesa;
use App\Models\Gasto;
use App\Models\Plano;
use App\Models\Renda;
use Illuminate\Support\Facades\Auth;

class DespesaObserver
{
    private function controleCusto(): void
    {
        $authId = Auth::user()->getAuthIdentifier();
        $receita = Renda::whereUserId($authId)->first();

        $date = \Illuminate\Support\Facades\Date::now();
        $mes = ($date->month >= 1 && $date->month <= 9) ? strval('0' . $date->month) : $date->month;
        $mesAno = sprintf('%s/%d', $mes, $date->year);

        $despesas = Plano::with('despesas')->where([
            ['user_id', '=', $authId],
            ['mes_ano', '>=', $mesAno],
        ])->first()?->toArray() ?? [];

        $planoId = $despesas['id'] ?? 0;

        if (! empty($receita)) {
            $total = 0.0;

            if (! empty($despesas)) {
                foreach ($despesas['despesas'] as $despesa) {
                    $total += (float) $despesa['valor_documento'];
                }

                $diferenca = (float) $receita->saldo - $total;
                $receita->custo = strval($diferenca);
                $receita->update();

                $gasto = Gasto::where('plano_id', '=', $planoId)->first();

                if (empty($gasto)) {
                    Gasto::create([
                        'plano_id' => $planoId,
                        'valor' => (string) $total,
                    ]);
                } else {
                    $gasto->valor = (string) $total;
                    $gasto->update();
                }
            }
        }
    }

    /**
     * Handle the Despesa "created" event.
     */
    public function saved(Despesa $despesa): void
    {
        $this->controleCusto();
    }

    /**
     * Handle the Despesa "updated" event.
     */
    public function updated(Despesa $despesa): void
    {
        $this->controleCusto();
    }

    /**
     * Handle the Despesa "deleted" event.
     */
    public function deleted(Despesa $despesa): void
    {
        $this->controleCusto();
    }

    /**
     * Handle the Despesa "restored" event.
     */
    public function restored(Despesa $despesa): void
    {
        //
    }

    /**
     * Handle the Despesa "force deleted" event.
     */
    public function forceDeleted(Despesa $despesa): void
    {
        //
    }
}
