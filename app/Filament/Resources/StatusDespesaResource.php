<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\StatusDespesaResource\Pages;
use App\Models\StatusDespesa;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class StatusDespesaResource extends Resource
{
    protected static ?string $model = StatusDespesa::class;

    protected static UnitEnum|string|null $navigationGroup = 'Configurações';

    protected static ?string $modelLabel = 'status da despesa';

    protected static ?string $pluralModelLabel = 'Status da despesa';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label(str('informe o status')->ucfirst()->__toString())
                    ->columnSpanFull()
                    ->prefix(str('status')->ucfirst()->__toString())
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
            'index' => Pages\ManageStatusDespesas::route('/'),
        ];
    }
}
