<?php

namespace App\Filament\Widgets;

use App\Models\Gasto;
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
        
        $gasto = Plano::with('gastos')->where([
            ['user_id', '=', $authId],
            ['mes_ano', '>=', $mesAno]
        ])->first();
       
        if ($gasto !== null )
            $gasto = $gasto->toArray();

        if (!empty($receita)) { 
            $total = strval($gasto['gastos']['valor']) ?? 0.0;

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
