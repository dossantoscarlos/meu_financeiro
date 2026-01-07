<?php

namespace App\Filament\Resources\HistoricoDespesas\Schemas;

use App\Models\HistoricoDespesa;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HistoricoDespesaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('despesa.id')
                    ->label('Despesa'),
                TextEntry::make('status_despesa.id')
                    ->label('Status despesa'),
                TextEntry::make('data')
                    ->date(),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (HistoricoDespesa $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
