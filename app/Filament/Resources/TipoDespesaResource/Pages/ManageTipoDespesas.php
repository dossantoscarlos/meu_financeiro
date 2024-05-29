<?php

namespace App\Filament\Resources\TipoDespesaResource\Pages;

use App\Filament\Resources\TipoDespesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoDespesas extends ManageRecords
{
    protected static string $resource = TipoDespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
