<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TipoDespesaResource\Pages;
use App\Models\TipoDespesa;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class TipoDespesaResource extends Resource
{
    protected static UnitEnum|string|null $navigationGroup = 'Configurações';

    protected static ?string $model = TipoDespesa::class;

    protected static ?string $modelLabel = 'tipo de despesa';

    protected static ?string $pluralModelLabel = 'Tipo de despesa';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->unique('nome')
                    ->label(str('Informe a tag')->upper()->__toString())
                    ->columnSpanFull()
                    ->prefix(str('tag')->ucfirst()->__toString())
                    ->afterStateUpdated(function (Page $page): void {
                        $page->validateOnly('data.nome');
                    })
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTipoDespesas::route('/'),
        ];
    }
}
