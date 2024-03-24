<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanoResource\Pages;
use App\Filament\Resources\PlanoResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make(name:'user_id')
                    ->label(label: 'Perfil')
                    ->relationship(
                        name:'user', 
                        titleAttribute:'name'
                    )
                    ->required(),
                Forms\Components\TextInput::make(name:'mes_ano')
                    ->label(label:'Mes e Ano')
                    ->placeholder(placeholder:'01/2023')
                    ->mask(mask:'99/9999')
                    ->required(),
                Forms\Components\TextInput::make(name:'descricao_simples')
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
}
