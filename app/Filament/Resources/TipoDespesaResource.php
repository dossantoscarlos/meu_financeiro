<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TipoDespesaResource\Pages\ManageTipoDespesas;
use App\Models\TipoDespesa;
use BackedEnum;
use Filament\Forms;
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
                    ->unique(table: 'tipo_despesas', column: 'nome', ignoreRecord: true)
                    ->label('Informe o tipo de despesa')
                    ->columnSpanFull()
                    ->prefix('Tipo de despesa')
                    ->afterStateUpdated(function ($livewire): void {
                        $livewire->validateOnly('data.nome');
                    })
                    ->required(),
            ]);
    }

    // The test method should typically be in a test file, not in the resource class itself.
    // However, following the instruction to insert it into the code document.
    // This method would not be called by Filament and would cause a PHP error if called directly.
    // Assuming this is a placeholder or a misunderstanding of where tests belong.
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
            'index' => ManageTipoDespesas::route('/'),
        ];
    }
}
