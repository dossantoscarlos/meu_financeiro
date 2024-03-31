<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class MyMoney extends TextInput
{
    protected function setUp(): void  
    {
        $this
            ->prefix("R$")
            ->maxLength(13)
            ->formatStateUsing(fn($state) : string => $state ?  number_format(floatval($state), 2, ',', '.') : $state)
            ->dehydrateStateUsing(fn ($state): ?float => $state ? floatval(Str::of($state)
                    ->replace('.', '')
                    ->replace(',', '.')
                    ->toString()
                ) : null
            )
            ->extraAlpineAttributes([
                'x-on:focus'=> 'function() {
                    if ($el.value == "0,00")
                        $el.value = "";
                }'
            ])
            ->default("0,00");
    }
}
