<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Produto;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProdutoTable extends BaseWidget
{
    protected static ?string $heading = 'Lista de produto';

    public function table(Table $table): Table
    {
        return $table
            ->query(Produto::query())
            ->columns([
                Tables\Columns\TextColumn::make('descricao_curta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preco')
                    ->money('BRL', locale: 'pt_BR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantidade')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->money('BRL', locale: 'pt_BR')
                    ->sortable(),
            ])
            ->paginated([3, 5, 10])
            ->defaultPaginationPageOption(3);
    }
}
