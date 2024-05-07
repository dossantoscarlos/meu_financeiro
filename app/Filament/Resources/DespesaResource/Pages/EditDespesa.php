<?php

namespace App\Filament\Resources\DespesaResource\Pages;

use App\Filament\Resources\DespesaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDespesa extends EditRecord
{
    protected static string $resource = DespesaResource::class;

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
