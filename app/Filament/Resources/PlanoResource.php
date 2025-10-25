<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PlanoResource\Pages;
use App\Models\Plano;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanoResource extends Resource
{
    protected static ?string $model = Plano::class;

    protected static ?string $navigationGroup = 'Financeiro';

    protected static ?string $modelLabel = 'Plano';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            ]);
    }
}
