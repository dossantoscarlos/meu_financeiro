<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Dispesa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\DispesaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DispesaResource\Pages\EditDispesa;
use App\Filament\Resources\DispesaResource\Pages\ListDispesas;
use App\Filament\Resources\DispesaResource\Pages\CreateDispesa;
use Illuminate\Support\Facades\Auth;
use Leandrocfe\FilamentPtbrFormFields\Money;

class DispesaResource extends Resource
{
    protected static ?string $model = Dispesa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->label(label: 'plano mensal')
                    ->relationship(
                        name: 'plano', 
                        titleAttribute: 'mes_ano',
                        modifyQueryUsing: fn($query) => $query->whereUserId(Auth::user()->id))
                    ->native(condition: false)
                    ->required(),
                Forms\Components\Select::make(name: 'status_dispesa_id')
                    ->label(label: 'Status')
                    ->relationship(name: 'statusDispesa', titleAttribute:'nome')
                    ->native(condition:false)
                    ->required(),
                Forms\Components\Select::make(name: 'tipo_dispesa_id')
                    ->label(label: "Categoria")
                    ->relationship(name: 'tipoDispesa', titleAttribute:'nome')
                    ->native(condition:false)
                    ->required(),
                Money::make(name: 'valor_documento')
                    ->label(label: 'Valor do documento')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(name: 'descricao')
                    ->searchable(),
                Tables\Columns\TextColumn::make(name: 'statusDispesa.nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'tipoDispesa.nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make(name: 'data_vencimento')
                    ->label('Data de vencimento')
                    ->date('d/m/Y')
                    ->searchable(),
                Tables\Columns\TextColumn::make(name: 'valor_documento')
                    ->label(label: 'Valor do documento')
                    ->formatStateUsing(fn(?string $state): string => "R$ ".number_format(floatval($state), 2, ',', '.'))
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
            'index' => ListDispesas::route('/'),
            'create' => CreateDispesa::route('/create'),
            'edit' => EditDispesa::route('/{record}/edit'),
        ];
    }
}
