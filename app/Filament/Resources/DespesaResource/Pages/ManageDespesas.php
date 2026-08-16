<?php

declare(strict_types=1);

namespace App\Filament\Resources\DespesaResource\Pages;

use App\Filament\Resources\DespesaResource;
use App\Filament\Resources\HistoricoDespesas\HistoricoDespesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDespesas extends ManageRecords
{
    protected static string $resource = DespesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('historico')
                ->label('Histórico de Alterações')
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->url(fn () => HistoricoDespesaResource::getUrl('index')),
            Actions\CreateAction::make(),
        ];
    }
}
