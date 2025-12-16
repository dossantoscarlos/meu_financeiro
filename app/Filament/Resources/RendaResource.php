<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\RendaResource\Pages;
use App\Livewire\Components\MyMoney;
use App\Models\Renda;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class RendaResource extends Resource
{
    protected static ?string $model = Renda::class;

    protected static ?string $modelLabel = 'Renda';

    public static ?string $pluralModelLabel = 'Rendas';

    protected static UnitEnum|string|null $navigationGroup = 'Financeiro';

    protected static BackedEnum|string|null $navigationIcon = 'vaadin-money-deposit';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->native(false)
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $builder): Builder =>
                            $builder->whereId(
                                Auth::user()->getAuthIdentifier()
                            )
                    )
                ->required(),
                MyMoney::make('saldo')
                    ->label('Renda')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('saldo')
                    ->label('Renda Inicial')
                    ->money(currency: 'BRL', locale: 'pt_BR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('custo')
                    ->label('Renda Atual')
                    ->money(currency: 'BRL', locale: 'pt_BR')
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
            'index' => Pages\ManageRendas::route('/'),
        ];
    }
}
