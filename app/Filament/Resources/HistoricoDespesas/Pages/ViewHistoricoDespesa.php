<?php

namespace App\Filament\Resources\HistoricoDespesas\Pages;

use App\Filament\Resources\HistoricoDespesas\HistoricoDespesaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHistoricoDespesa extends ViewRecord
{
    protected static string $resource = HistoricoDespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
