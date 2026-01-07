<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Plano;
use App\Models\Renda;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class CustoWidget extends BaseWidget
{
    protected ?string $pollingInterval = '5s';

    private function brlMoeda(float $data): string
    {
        return 'R$ ' . number_format(floatval($data), 2, ',', '.');
    }

    private function msgDescriptionCustom(bool $status, string $campo): string
    {
        return $status ? $campo . ' positivo(a)' : $campo . ' negativo(a)';
    }

    private function customColorStat(bool $status): string
    {
        return $status ? 'success' : 'danger';
    }

    private function statCustom(string $texto, float $valor, bool $condicional, string $description): Stat
    {
        return Stat::make($texto, $this->brlMoeda($valor))
            ->description($this->msgDescriptionCustom($condicional, $description))
            ->color($this->customColorStat($condicional));
    }

    protected function getStats(): array
    {
        $authId = Auth::user()->getAuthIdentifier();
        $renda = Renda::where([
            ['user_id', '=', $authId],
        ])->first();

        $plano = Plano::with('gastos')->where([
            ['user_id', '=', $authId]
        ])->latest('created_at')->first();

        $total = floatval($plano?->gastos?->valor ?? 0.0);
        $saldo = $renda->saldo ?? 0.0;
        $custo = $saldo - $total;

        info(sprintf('%s | %s | %s', $total, $saldo, $custo));

        return [
            Stat::make('Renda Inicial', $this->brlMoeda((float) $saldo)),
            $this->statCustom('Custo previsto', (float) $total, $saldo >= $total, 'Custo'),
            $this->statCustom('Renda atual', (float) $custo, $saldo > $total, 'Renda'),
        ];
    }
}
