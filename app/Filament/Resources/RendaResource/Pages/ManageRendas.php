<?php

declare(strict_types=1);

namespace App\Filament\Resources\RendaResource\Pages;

use App\Filament\Resources\RendaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRendas extends ManageRecords
{
    protected static string $resource = RendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
