<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DespesaResource\Pages;
use App\Livewire\Components\MyMoney;
use App\Models\Despesa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DespesaResource extends Resource
{
    protected static ?string $model = Despesa::class;

    protected static ?string $modelLabel = 'Despesa';

    protected static ?string $navigationGroup = 'Financeiro';

    protected static ?string $pluralModelLabel = 'Despesas';

    protected static ?string $icon = 'heroicon-o-credit-card';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {

        return $form
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
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ])
            )->columns([
                Tables\Columns\TextColumn::make(name: 'descricao')
                    ->searchable(),
                Tables\Columns\TextColumn::make(name: 'statusDespesa.nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'tipoDespesa.nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'plano.mes_ano')
                    ->tooltip(fn (Model $record): string => "{$record->plano->descricao_simples}")
                    ->searchable(),
                Tables\Columns\TextColumn::make(name: 'data_vencimento')
                    ->label('Data de vencimento')
                    ->date('d/m/Y')
                    ->searchable(),
                Tables\Columns\TextColumn::make(name: 'valor_documento')
                    ->label(label: 'Valor do documento')
                    ->money(currency: 'BRL', locale: 'pt_BR')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

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
            ]);
    }
}
