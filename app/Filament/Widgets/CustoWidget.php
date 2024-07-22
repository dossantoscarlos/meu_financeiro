<?php

namespace App\Filament\Widgets;

use App\Models\Plano;
use App\Models\Renda;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustoWidget extends BaseWidget
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


        $total = strval($plano['gastos']['valor'] ?? 0.0);
        Log::debug("total de gastos: {$total}");

        $saldo = $renda->saldo ?? 0;
        $custo = $renda->custo ?? 0;


        return [
            Stat::make('Renda Inicial', $this->brl_moeda($saldo)),
            Stat::make('Custo previsto',$this->brl_moeda($total))
                ->description( $this->msgDescriptionCustomCusto($saldo < $total))
                ->color($this->customColorStat($total < $saldo)),
            Stat::make('Renda atual', $this->brl_moeda( $custo))
                ->description($this->msgDescriptionCustomRenda($saldo > $total))
                ->color($this->customColorStat($saldo > $total)),
        ];
    }

    private function msgDescriptionCustomCusto(bool $status): string {
        return $status ? 'Gasto negativo' : 'Gasto positivo';
    }

    private function msgDescriptionCustomRenda(bool $status): string {
        return $status ? 'Renda positiva':  'Renda negativa' ;
    }

    private function customColorStat(bool $status): string {
        return $status ? 'success' : 'danger';
    }
}
