<?php

namespace App\Filament\Widgets;

use App\Models\Plano;
use App\Models\Receita;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $authId = Auth::user()->getAuthIdentifier();
        $receita = Receita::whereUserId($authId)->first();
        $date = Carbon::now();

        $mes = ($date->month >= 1 && $date->month <= 9) ? strval('0'.$date->month) : $date->month;
        $ano = $date->year;
        $mesAno = "{$mes}/{$ano}";

        $plano = Plano::with('gastos')->where([
            ['user_id', '=', $authId],
            ['mes_ano', '>=', $mesAno],
        ])->first()?->toArray() ?? [];

        $stat = [
            Stat::make('Renda Inicial', $receita->saldo ?? 'R$ 0,00'),
            Stat::make('Custo previsto', 'R$ 0,00'),
            Stat::make('Renda atual', $receita->saldo ?? 'R$ 0,00'),
        ];

        if (empty($plano) || $plano['gastos'] == null) {
            Log::debug('array de plano esta vazio '.now(), $plano);

            return $stat;
        }

        if (! empty($receita)) {

            $total = strval($plano['gastos']['valor']) ?? 0.0;
            ds('total de gastos: ', $total);

            return [
                Stat::make('Renda inicial', 'R$ '.number_format(floatval($receita->saldo), 2, ',', '.')),
                Stat::make('Custo previsto', 'R$ '.number_format(floatval($total), 2, ',', '.')),
                Stat::make('Renda atual', 'R$ '.number_format(floatval($receita->custo ?? $receita->saldo), 2, ',', '.')),
            ];
        }

        return $stat;
    }
}
