<?php

namespace App\Filament\Resources\TipoDispesaResource\Pages;

use App\Filament\Resources\TipoDispesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoDispesas extends ManageRecords
{
    protected static string $resource = TipoDispesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
