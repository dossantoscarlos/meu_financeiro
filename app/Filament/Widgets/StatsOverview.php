<?php

namespace App\Filament\Widgets;

use App\Models\Dispesa;
use App\Models\Plano;
use App\Models\Receita;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $authId = Auth::user()->getAuthIdentifier();
        $receita = Receita::whereUserId($authId)->first();
        $date = Carbon::now();
        $mes ='';
        $ano = $date->year;

        if ($date->month >= 1 && $date->month <= 9) {
            $mes = strval("0".$date->month);
        }   

        $mesAno = "{$mes}/{$ano}";
        
        $dispesas = Plano::with('dispesa')->where([
                ['user_id', '=', $authId],
                ['mes_ano', '=', $mesAno]
            ])->first()->toArray();

        if (!empty($receita)) { 
            
            $total = 0.0;
            $custo = 0.0;
            
            if (!empty($dispesas)) {
                foreach($dispesas['dispesa'] as $dispesa) {
                    $total += (float) $dispesa['valor_documento'];
                }

                $custo = $receita->saldo - $total;

                $total = strval($total);
                $custo = strval($custo);

                $receita->custo = $custo;
                $receita->update();
                $receita->save();
            }

            return [
                Stat::make('Renda inicial', "R$ ".number_format(floatval($receita->saldo), 2, ',', '.')),
                Stat::make('Custo mensal',"R$ ".number_format(floatval($total), 2, ',', '.')),
                Stat::make('Renda final', 'R$ '.number_format(floatval($custo), 2, ',', '.')),
            ]; 
        }

        return [
            Stat::make('Salario', "Salario não registrado."),
            Stat::make('Custo mensal',"R$ 0,00"),
            Stat::make('Renda final', 'R$ 0,00'),
        ];

    }
}
