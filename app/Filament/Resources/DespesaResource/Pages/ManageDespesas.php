<?php

declare(strict_types=1);

namespace App\Filament\Resources\DespesaResource\Pages;

use App\Filament\Resources\DespesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDespesas extends ManageRecords
{
    protected static string $resource = DespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
