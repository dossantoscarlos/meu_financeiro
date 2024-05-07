<?php

namespace App\Filament\Resources\DespesaResource\Pages;

use App\Filament\Resources\DespesaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDespesa extends CreateRecord
{
    protected static string $resource = DespesaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
