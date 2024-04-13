<?php

namespace App\Filament\Widgets;

use App\Models\TipoDispesa;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ListaCategoria extends BaseWidget
{

    public function table(Table $table): Table
    {
        return $table
            ->query(TipoDispesa::query())
            ->columns([
                Tables\Columns\TextColumn::make('nome')
            ]);
    }
}
