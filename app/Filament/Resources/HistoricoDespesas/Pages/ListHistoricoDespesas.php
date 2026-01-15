<?php

declare(strict_types=1);

namespace App\Filament\Resources\HistoricoDespesas\Pages;

use App\Filament\Resources\HistoricoDespesas\HistoricoDespesaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHistoricoDespesas extends ListRecords
{
    protected static string $resource = HistoricoDespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
