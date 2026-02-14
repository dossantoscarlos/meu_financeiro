<?php

declare(strict_types=1);

namespace App\Filament\Resources\Caixinhas;

use App\Filament\Resources\Caixinhas\Pages\ManageCaixinhas;
use App\Models\Caixinha;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use UnitEnum;

class CaixinhaResource extends Resource
{
    protected static ?string $model = Caixinha::class;

    protected static UnitEnum|string|null $navigationGroup = 'Operação';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    private static function calculateValorParcela(float $valor_produto, float $parcelas): float
    {
        Log::info($valor_produto);
        Log::info($parcelas);

        $valor_parcela = floatval($valor_produto / $parcelas);

        Log::info($valor_parcela);

        return $valor_parcela;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('descricao')
                    ->required(),
                TextInput::make('valor_produto')
                    ->required()
                    ->live(debounce: 1)
                    ->numeric()
                    ->afterStateUpdated(function (Get $get, Set $set, mixed $state) {
                        if (!is_numeric($get('parcelas'))) {
                            $set('valor_parcela', $state);
                            $set('parcelas', 1);
                        }

                        if (is_numeric($get('parcelas')) && $get('parcelas') > 0) {
                            $set('valor_parcela', self::calculateValorParcela(
                                $get('valor_produto'),
                                $get('parcelas')
                            ));
                        }
                    }),
                TextInput::make('parcelas')
                    ->required()
                    ->minValue(1)
                    ->live(debounce: 1)
                    ->afterStateUpdated(function (Get $get, Set $set, mixed $state) {
                        $parcelas = $state;
                        Log::info($parcelas);

                        if (!is_numeric($get('valor_produto'))) {
                            $set('valor_produto', 0);
                        }

                        if (is_numeric($parcelas) && $parcelas > 0) {
                            $set('valor_parcela', self::calculateValorParcela($get('valor_produto'), $parcelas));
                        } else {
                            $state = 1;
                            $set('valor_parcela', $get('valor_produto'));
                        }
                    })
                    ->numeric(),

                TextInput::make('valor_parcela')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('descricao')
                    ->searchable(),
                TextColumn::make('valor_produto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('parcelas')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valor_parcela')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCaixinhas::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
