<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Models\StatusDespesa;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
        return $schema->schema([
            Forms\Components\TextInput::make('descricao')->required(),

            Forms\Components\DatePicker::make('data_vencimento')
                ->label('Data de vencimento')
                ->required(),

            Forms\Components\Select::make('plano_id')
                ->label('Plano mensal')
                ->relationship(
                    'plano',
                    'mes_ano',
                    fn ($query) => $query->where('user_id', Auth::id())
                )
                ->native(false)
                ->required(),

            Forms\Components\Select::make('status_despesa_id')
                ->label('Status')
                ->relationship('statusDespesa', 'nome')
                ->native(false)
                ->required(),

            Forms\Components\Select::make('tipo_despesa_id')
                ->label('Categoria')
                ->relationship('tipoDespesa', 'nome')
                ->native(false)
                ->searchable()
                ->required(),

            MyMoney::make('valor_documento')
                ->label('Valor do documento')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->label('Descrição')
                    ->searchable(),

                Tables\Columns\TextColumn::make('statusDespesa.nome')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => StatusDespesaColor::getColor($state))
                    ->formatStateUsing(fn ($state) => mb_strtoupper($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipoDespesa.nome')
                    ->label('Categoria')
                    ->formatStateUsing(fn (?string $state) => mb_strtoupper($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('plano.mes_ano')
                    ->label('Plano mensal')
                    ->formatStateUsing(fn (string $state) => mb_strtoupper($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('data_vencimento')
                    ->label('Data de vencimento')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('valor_documento')
                    ->label('Valor do documento')
                    ->money('brl')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_despesa_id')
                    ->label('Status')
                    ->multiple()
                    ->options([
                        StatusDespesa::PENDENTE => 'Pendente',
                        StatusDespesa::ATRASADO => 'Atrasado',
                        StatusDespesa::PAGO => 'Pago',
                    ])
                    ->default([
                        StatusDespesa::PENDENTE,
                        StatusDespesa::ATRASADO
                    ])
                    ->column('despesas.status_despesa_id'),
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
                    ->action(
                        fn (Collection $records) =>
                        $records->each->delete()
                    ),
            ])
            ->defaultPaginationPageOption(5);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->join('planos', 'despesas.plano_id', '=', 'planos.id')
            ->where('planos.user_id', Auth::id())
            ->whereIn('despesas.status_despesa_id', [
                StatusDespesa::PENDENTE,
                StatusDespesa::ATRASADO,
                StatusDespesa::PAGO
            ])
            ->select('despesas.*')
            ->distinct();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDespesas::route('/'),
        ];
    }
}
