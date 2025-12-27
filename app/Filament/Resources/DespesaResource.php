<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DespesaResource\Pages;
use App\Livewire\Components\MyMoney;
use App\Models\Despesa;
use App\Util\StatusDespesaColor;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class DespesaResource extends Resource
{
    protected static ?string $model = Despesa::class;

    protected static ?string $modelLabel = 'Despesa';

    protected static string|UnitEnum|null $navigationGroup = 'Financeiro';

    protected static ?string $pluralModelLabel = 'Despesas';

    protected static ?string $icon = 'heroicon-o-credit-card';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make(name: 'descricao')
                    ->required(),
                Forms\Components\DatePicker::make(name: 'data_vencimento')
                    ->label(label: 'Data de vencimento')
                    ->required(),
                Forms\Components\Select::make(name: 'plano_id')
                    ->label(label: 'Plano mensal')
                    ->relationship(
                        name: 'plano',
                        titleAttribute: 'mes_ano',
                        modifyQueryUsing: fn ($query) => $query->whereUserId(Auth::user()->id)
                    )
                    ->native(condition: false)
                    ->createOptionForm([
                        Forms\Components\Select::make(name: 'user_id')
                            ->label(label: 'Perfil')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name'
                            )
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make(name: 'mes_ano')
                            ->label(label: 'Mes e Ano')
                            ->placeholder(placeholder: '01/2023')
                            ->mask(mask: '99/9999')
                            ->required(),
                        Forms\Components\TextInput::make(name: 'descricao_simples')
                            ->columnSpanfull()
                            ->placeholder(placeholder: 'Ex.: Controle do mês de janeiro')
                            ->required(),
                    ])
                    ->required(),
                Forms\Components\Select::make(name: 'status_despesa_id')
                    ->label(label: 'Status')
                    ->relationship(name: 'statusDespesa', titleAttribute: 'nome')
                    ->native(condition: false)
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nome')
                            ->required(),
                    ])
                    ->required(),
                Forms\Components\Select::make(name: 'tipo_despesa_id')
                    ->label(label: 'Categoria')
                    ->relationship(name: 'tipoDespesa', titleAttribute: 'nome')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nome')
                            ->required(),
                    ])
                    ->searchable()
                    ->searchDebounce(100)
                    ->native(condition: false)
                    ->required(),
                MyMoney::make(name: 'valor_documento')
                    ->label(label: 'Valor do documento')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(name: 'descricao')
                    ->label(label: 'Descrição')
                    ->searchable(),
                Tables\Columns\TextColumn::make(name: 'statusDespesa.nome')
                    ->label(label: 'Status')
                    ->badge()
                    ->color(fn (string $state): string => StatusDespesaColor::getColor($state))
                    ->formatStateUsing(fn (string $state): string => mb_strtoupper($state))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'tipoDespesa.nome')
                    ->label(label: 'Categoria')
                    ->formatStateUsing(fn (string $state): string => mb_strtoupper($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'plano.mes_ano')
                    ->label(label: 'Plano mensal')
                    ->formatStateUsing(fn (string $state): string => mb_strtoupper($state))
                    ->tooltip(fn (Model $model): ?string => $model->plano?->descricao_simples)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'data_vencimento')
                    ->label('Data de vencimento')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'valor_documento')
                    ->label(label: 'Valor do documento')
                    ->money(currency: 'BRL', locale: 'pt_BR')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make(name: 'created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make(name: 'updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('ocultar_pagos')
                    ->label('Ocultar pagos')
                    ->query(
                        fn (Builder $query) =>
                        $query->whereDoesntHave(
                            'statusDespesa',
                            fn (Builder $query) =>
                            $query->where('nome', 'pago')
                        )
                    )
                    ->default(),
            ])
        ->recordActions([
            Actions\ActionGroup::make([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
                Actions\RestoreAction::make(),
                Actions\ForceDeleteAction::make(),
            ]),
        ])
        ->toolbarActions([
            Actions\BulkAction::make('delete')
            ->label('Deletar')
            ->action(function (Collection $records) {
                $records->each(function (Model $record) {
                    $record->delete();
                });
            })
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDespesas::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereHas('plano', fn ($query) => $query->where('user_id', Auth::id()));
    }
}
