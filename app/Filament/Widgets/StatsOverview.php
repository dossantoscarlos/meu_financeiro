<?php

namespace App\Filament\Widgets;

use App\Models\Plano;
use App\Models\Receita;
use Exception;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\Expect;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $authId = Auth::user()->getAuthIdentifier();
        $receita = Receita::whereUserId($authId)->first();
        $date = Carbon::now();
        
        $mes = ($date->month >= 1 && $date->month <= 9) ? strval("0".$date->month) : $date->month;
        $ano = $date->year;
        $mesAno = "{$mes}/{$ano}";
        
        $dispesas = Plano::with('dispesa')->where([
            ['user_id', '=', $authId],
            ['mes_ano', '>=', $mesAno]
        ])->first();
       
        if ($dispesas!== null )
            $dispesas = $dispesas->toArray();
        else 
            $dispesas = [];

        if (!empty($receita)) { 
            $total = 0.0;
            if (!empty($dispesas)) {
                foreach($dispesas['dispesa'] as $dispesa) {
                    $total += (float) $dispesa['valor_documento'];
                }
                $total = strval($total);
            }

            return [
                Stat::make('Renda inicial', "R$ ".number_format(floatval($receita->saldo), 2, ',', '.')),
                Stat::make('Custo previsto',"R$ ".number_format(floatval($total), 2, ',', '.')),
                Stat::make('Renda atual', 'R$ '.number_format(floatval($receita->custo ?? $receita->saldo), 2, ',', '.')),
            ]; 
        }

        return [
            Stat::make('Salario', "Salario não registrado."),
            Stat::make('Custo previsto',"R$ 0,00"),
            Stat::make('Renda atual', $receita->saldo ?? "R$ 0,00"),
        ];

    }
}
