<?php

namespace App\Filament\Resources\StatusDispesaResource\Pages;

use App\Filament\Resources\StatusDispesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStatusDispesas extends ManageRecords
{
    protected static string $resource = StatusDispesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
