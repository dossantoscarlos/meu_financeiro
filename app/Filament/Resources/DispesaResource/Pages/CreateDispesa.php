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
            Action::make('Criar status')
                ->form([
                    TextInput::make('nome')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    Log::debug('capturando data de action ', $data);
                    $status = new StatusDispesa;
                    $status->nome = $data['nome'];
                    $status->save();
                }),
            //->dispatch('created'),
            Action::make('Criar tipo')
                ->form([
                    TextInput::make('nome')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    Log::debug('capturando data de action ', $data);
                    $status = new TipoDispesa;
                    $status->nome = $data['nome'];
                    $status->save();
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
