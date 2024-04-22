<?php

namespace App\Filament\Widgets;

use App\Models\Produto;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Log;

class ProdutoTable extends BaseWidget
{

    public function table(Table $table): Table
    {

        $produto = Produto::query();

        Log::debug('produto', (array) $produto);

        return $table
            ->query($produto)
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
