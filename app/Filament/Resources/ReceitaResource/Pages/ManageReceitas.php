<?php

namespace App\Filament\Resources\ReceitaResource\Pages;

use App\Filament\Resources\ReceitaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageReceitas extends ManageRecords
{
    protected static string $resource = ReceitaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
