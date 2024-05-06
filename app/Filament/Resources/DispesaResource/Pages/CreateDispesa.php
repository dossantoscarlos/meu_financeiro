<?php

namespace App\Filament\Resources\DispesaResource\Pages;

use App\Filament\Resources\DispesaResource;
use App\Models\StatusDispesa;
use App\Models\TipoDispesa;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateDispesa extends CreateRecord
{
    protected static string $resource = DispesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
