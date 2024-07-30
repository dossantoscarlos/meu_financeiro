<?php

namespace App\Filament\Widgets;

use App\Models\Plano;
use App\Models\Renda;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CustoWidget extends BaseWidget
{

    private function brl_moeda(float $data) : string
    {
        return 'R$ '.number_format(floatval($data), 2, ',', '.');
    }

    private function msgDescriptionCustom(bool $status, string $campo ): string {
        return $status ? "{$campo} positivo(a)": "{$campo} negativo(a)" ;
    }

    private function customColorStat(bool $status): string {
        return $status ? 'success' : 'danger';
    }

    private function stat_custom(string $texto, float $valor, bool $condicional, $description) : Stat
    {
        return Stat::make("{$texto}", $this->brl_moeda($valor))
            ->description($this->msgDescriptionCustom($condicional, $description))
            ->color($this->customColorStat($condicional));
    }

    protected function getStats(): array
    {
        $authId = Auth::user()->getAuthIdentifier();
        $renda = Renda::whereUserId($authId)->first();
        $date = Carbon::now();
        $mes = ($date->month >= 1 && $date->month <= 9) ? strval("0{$date->month}") : $date->month;
        $ano = $date->year;
        $mesAno = "{$mes}/{$ano}";

        $plano = Plano::with('gastos')->where([
            ['user_id', '=', $authId],
            ['mes_ano', '>=', $mesAno],
        ])->first()?->toArray() ?? [];

        $total = strval($plano['gastos']['valor'] ?? 0.0);
        $saldo = $renda->saldo ?? 0.0;
        $custo = $renda->custo ?? 0.0;

        info("{$total} | {$saldo} | {$custo}");

        return [
            Stat::make('Renda Inicial', $this->brl_moeda($saldo)),
            $this->stat_custom('Custo previsto', $total, !($saldo < $total), "Custo"),
            $this->stat_custom('Renda atual', $custo, $saldo > $total, "Renda"),
        ];
    }
}
