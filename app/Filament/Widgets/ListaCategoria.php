<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\TipoDespesa;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ListaCategoria extends BaseWidget
{
    protected static ?string $heading = 'Categorias';

    public function table(Table $table): Table
    {
        return $table
            ->query(TipoDespesa::query())
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                ->searchable(),
            ])
            ->paginated([3, 5, 10])
            ->defaultPaginationPageOption(3);
    }
}
