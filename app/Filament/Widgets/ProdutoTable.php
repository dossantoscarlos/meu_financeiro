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
                Tables\Columns\TextColumn::make('descricao_curta'),
                Tables\Columns\TextColumn::make('preco')
                    ->money('BRL', locale: 'pt_BR'),
                Tables\Columns\TextColumn::make('quantidade'),
                Tables\Columns\TextColumn::make('total')
                    ->money('BRL', locale: 'pt_BR'),
            ]);
    }
}
