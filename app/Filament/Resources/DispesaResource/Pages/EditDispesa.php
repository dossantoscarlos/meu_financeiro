<?php

namespace App\Filament\Resources\DispesaResource\Pages;

use App\Filament\Resources\DispesaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDispesa extends EditRecord
{
    protected static string $resource = DispesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
