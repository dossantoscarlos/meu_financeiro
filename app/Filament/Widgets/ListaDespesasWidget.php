<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Despesa;
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
        return $table
            ->query(
                Despesa::query()
                    ->whereHas('plano', function ($query) {
                        $query->where('user_id', Auth::id())->whereD;
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
