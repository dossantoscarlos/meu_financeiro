<?php

declare(strict_types=1);

namespace App\Filament\Resources\HistoricoDespesas\Pages;

use App\Filament\Resources\HistoricoDespesas\HistoricoDespesaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditHistoricoDespesa extends EditRecord
{
    protected static string $resource = HistoricoDespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
