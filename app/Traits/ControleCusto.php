<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Gasto;
use App\Models\Plano;
use App\Models\Renda;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

trait ControleCusto
{
    private function controleCusto(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $authId = $user->getAuthIdentifier();
        $renda = Renda::whereUserId($authId)->first();

        if (!$renda) {
            return;
        }

        $date = Date::now();
        $mes = ($date->month >= 1 && $date->month <= 9) ? strval('0' . $date->month) : (string) $date->month;
        $mesAno = sprintf('%s/%d', $mes, $date->year);

        $plano = Plano::with('despesas')->where([
            ['user_id', '=', $authId],
            ['mes_ano', '>=', $mesAno],
        ])->first();

        if (!$plano) {
            return;
        }

        $total = 0.0;
        foreach ($plano->despesas as $despesa) {
            $total += (float) $despesa->valor_documento;
        }

        $diferenca = (float) $renda->saldo - $total;
        $renda->custo = (string) $diferenca;
        $renda->save();

        $gasto = Gasto::where('plano_id', '=', $plano->id)->first();

        if (empty($gasto)) {
            Gasto::create([
                'plano_id' => $plano->id,
                'valor' => (string) $total,
            ]);
        } else {
            $gasto->valor = (string) $total;
            $gasto->save();
        }
    }
}
