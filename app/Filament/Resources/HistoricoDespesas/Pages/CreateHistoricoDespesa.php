<?php

declare(strict_types=1);

namespace App\Filament\Resources\HistoricoDespesas\Pages;

use App\Filament\Resources\HistoricoDespesas\HistoricoDespesaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHistoricoDespesa extends CreateRecord
{
    protected static string $resource = HistoricoDespesaResource::class;
}
