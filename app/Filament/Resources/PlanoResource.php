<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PlanoResource\Pages;
use App\Models\Plano;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use UnitEnum;

class PlanoResource extends Resource
{
    protected static ?string $model = Plano::class;

    protected static UnitEnum|string|null $navigationGroup = 'Financeiro';

    protected static ?string $modelLabel = 'Plano';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make(name: 'user_id')
                    ->label(label: 'Perfil')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name'
                    )
                    ->native(condition: false)
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(name: 'descricao_simples')
                    ->searchable(),
                Tables\Columns\TextColumn::make(name: 'mes_ano')
                    ->searchable(),
                Tables\Columns\TextColumn::make(name: 'user.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\ActionGroup::make([
                    Actions\EditAction::make(),
                    Actions\ViewAction::make(),
                    Actions\DeleteAction::make(),
                    Actions\RestoreAction::make(),
                    Actions\ForceDeleteAction::make(),
                ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePlanos::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->where('user_id', \Illuminate\Support\Facades\Auth::id());
    }
}
