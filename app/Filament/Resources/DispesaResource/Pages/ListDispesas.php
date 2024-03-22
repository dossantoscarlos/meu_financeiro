<?php

namespace App\Filament\Resources\DispesaResource\Pages;

use App\Filament\Resources\DispesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDispesas extends ListRecords
{
    protected static string $resource = DispesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
