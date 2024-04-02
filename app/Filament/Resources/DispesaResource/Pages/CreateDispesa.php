<?php

namespace App\Filament\Resources\DispesaResource\Pages;

use App\Filament\Resources\DispesaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDispesa extends CreateRecord
{
    protected static string $resource = DispesaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
