<?php

declare(strict_types=1);

namespace App\Filament\Resources\HistoricoDespesas\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HistoricoDespesasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('despesa.descricao')
                    ->label(label: 'Despesa')
                    ->searchable(),
                TextColumn::make('status_despesa.nome')
                    ->label(label: 'Status')
                    ->searchable(),
                TextColumn::make('data')
                    ->label(label: 'Data')
                    ->date()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label(label: 'Deletado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(label: 'Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
            ])
            ->toolbarActions([
            ]);
    }
}
