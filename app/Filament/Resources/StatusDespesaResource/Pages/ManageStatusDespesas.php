<?php

namespace App\Filament\Resources\StatusDespesaResource\Pages;

use App\Filament\Resources\StatusDespesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStatusDespesas extends ManageRecords
{
    protected static string $resource = StatusDespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
