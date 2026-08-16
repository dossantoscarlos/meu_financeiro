<?php

declare(strict_types=1);

namespace App\Filament\Resources\Caixinhas\Pages;

use App\Filament\Resources\Caixinhas\CaixinhaResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class Caixinha extends Page
{
    use InteractsWithRecord;

    protected static string $resource = CaixinhaResource::class;

    protected string $view = 'filament.resources.caixinhas.pages.caixinha';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
