<?php

declare(strict_types=1);

namespace App\Filament\Resources\PlanoResource\Pages;

use App\Filament\Resources\PlanoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePlanos extends ManageRecords
{
    protected static string $resource = PlanoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
