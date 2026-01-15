<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\StatusDespesa;
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

        $planIds = Plano::where('user_id', $userId)->pluck('id')->toArray();

        return $table
            ->query(
                Despesa::query()
                    ->where(function ($query) use ($planIds) {
                        $query->whereIn('plano_id', $planIds)
                            ->whereHas('statusDespesa', function ($sq) {
                                $sq->whereIn('id', [
                                    StatusDespesa::ATRASADO,
                                    StatusDespesa::PENDENTE
                                ]);
                            });
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
                    ->color(fn ($state): ?string => StatusDespesaColor::getColor($state))
                    ->formatStateUsing(fn ($state): string => mb_strtoupper($state))
                    ->sortable(),
            ])
            ->paginated([3, 5, 10])
            ->defaultPaginationPageOption(3);
    }
}
