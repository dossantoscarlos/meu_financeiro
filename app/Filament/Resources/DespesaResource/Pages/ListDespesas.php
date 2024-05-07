<?php

namespace App\Filament\Resources\DespesaResource\Pages;

use App\Filament\Resources\DespesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDespesas extends ListRecords
{
    protected static string $resource = DespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
