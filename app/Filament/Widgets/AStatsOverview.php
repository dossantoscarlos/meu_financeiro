<?php

namespace App\Filament\Widgets;

use App\Models\Plano;
use App\Models\Renda;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AStatsOverview extends BaseWidget
{

    private function brl_moeda($data) : string
    {
        return 'R$ '.number_format(floatval($data), 2, ',', '.');
    }


    protected function getStats(): array
    {

        $authId = Auth::user()->getAuthIdentifier();
        $renda = Renda::whereUserId($authId)->first();
        $date = Carbon::now();

        $total = 0.0;
        $mes = ($date->month >= 1 && $date->month <= 9) ? strval("0{$date->month}") : $date->month;
        $ano = $date->year;
        $mesAno = "{$mes}/{$ano}";

        $plano = Plano::with('gastos')->where([
            ['user_id', '=', $authId],
            ['mes_ano', '>=', $mesAno],
        ])->first()?->toArray() ?? [];

        if (!empty($renda) && sizeof($plano) > 0) {
            $total = strval($plano['gastos']['valor']) ?? 0.0;
            Log::debug("total de gastos: {$total}");
        }

        return [
            Stat::make('Renda Inicial', $this->brl_moeda($renda->saldo ?? 0)),
            Stat::make('Custo previsto',$this->brl_moeda($total)),
            Stat::make('Renda atual', $this->brl_moeda($renda->custo ?? $renda->saldo ?? 0)),
        ];
    }
}
