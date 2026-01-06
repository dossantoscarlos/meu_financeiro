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
        /** @var \Illuminate\Contracts\Auth\Authenticatable|null|\App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return;
        }

        /** @var int $authId */
        $authId = $user->getAuthIdentifier();

        /** @var Renda|null $renda */
        $renda = Renda::whereUserId($authId)->first();

        if (!$renda) {
            return;
        }

        /** @var \Illuminate\Support\Carbon $date */
        $date = Date::now();

        /** @var string $mes */
        $mes = ($date->month >= 1 && $date->month <= 9) ? strval('0' . $date->month) : (string) $date->month;

        /** @var string $mesAno */
        $mesAno = sprintf('%s/%d', $mes, $date->year);

        /** @var Plano|null $plano */
        $plano = Plano::with('despesas')->where([
            ['user_id', '=', $authId],
            ['mes_ano', '>=', $mesAno],
        ])->first();

        if (!$plano) {
            return;
        }

        /** @var float $total */
        $total = 0.0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Despesa> $despesas */
        $despesas = $plano->despesas;

        foreach ($despesas as $despesa) {
            $total += (float) $despesa->valor_documento;
        }

        /** @var float $diferenca */
        $diferenca = (float) $renda->saldo - $total;

        /** @var Renda $renda */
        $renda->custo = (string) $diferenca;

        $renda->save();

        /** @var Gasto|null $gasto */
        $gasto = Gasto::where('plano_id', '=', $plano->id)->first();

        if (empty($gasto)) {
            Gasto::create([
                'plano_id' => $plano->id,
                'valor' => (string) $total,
            ]);
        } else {
            /** @var Gasto $gasto */
            $gasto->valor = (string) $total;
            $gasto->save();
        }
    }
}
