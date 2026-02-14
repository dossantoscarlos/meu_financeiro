<?php

namespace App\Filament\Resources\Caixinhas\Pages;

use App\Filament\Resources\Caixinhas\CaixinhaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCaixinhas extends ManageRecords
{
    protected static string $resource = CaixinhaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
