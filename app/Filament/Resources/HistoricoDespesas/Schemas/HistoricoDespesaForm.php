<?php

namespace App\Filament\Resources\HistoricoDespesas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class HistoricoDespesaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('despesa_id')
                    ->relationship('despesa', 'id')
                    ->required(),
                Select::make('status_despesa_id')
                    ->relationship('status_despesa', 'id')
                    ->required(),
                DatePicker::make('data')
                    ->required(),
            ]);
    }
}
