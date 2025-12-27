<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Despesa;
use App\Models\Plano;
use App\Util\StatusDespesaColor;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class ListaDespesasWidget extends BaseWidget
{
    protected static ?string $heading = 'Resumo de despesas';

    public function table(Table $table): Table
    {
        $userId = Auth::id();
        $currentDate = \Illuminate\Support\Facades\Date::now()->startOfMonth();

        $planos = Plano::where('user_id', $userId)->get(['id', 'mes_ano']);
        $currentPlanIds = [];
        $pastPlanIds = [];

        foreach ($planos as $plano) {
            try {
                $planoDate = \Illuminate\Support\Facades\Date::createFromFormat('m/Y', $plano->mes_ano)->startOfMonth();
                if ($planoDate->equalTo($currentDate)) {
                    $currentPlanIds[] = $plano->id;
                } elseif ($planoDate->lessThan($currentDate)) {
                    $pastPlanIds[] = $plano->id;
                }
            } catch (\Exception) {
                // Ignore invalid dates
            }
        }

        return $table
            ->query(
                Despesa::query()
                    ->where(function ($query) use ($currentPlanIds, $pastPlanIds) {
                        $query->whereIn('plano_id', $currentPlanIds);

                        if (! empty($pastPlanIds)) {
                            $query->orWhere(function ($q) use ($pastPlanIds) {
                                $q->whereIn('plano_id', $pastPlanIds)
                                    ->whereHas('statusDespesa', function ($sq) {
                                        $sq->whereIn('nome', ['atrasado', 'pendente']);
                                    });
                            });
                        }
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor_documento')
                    ->label('Valor')
                    ->money('BRL', locale: 'pt_BR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('statusDespesa.nome')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => StatusDespesaColor::getColor($state))
                    ->formatStateUsing(fn (string $state): string => mb_strtoupper($state))
                    ->sortable(),
            ])
            ->paginated([3, 5, 10])
            ->defaultPaginationPageOption(3);
    }
}
