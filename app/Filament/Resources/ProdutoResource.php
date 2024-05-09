<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages;
use App\Livewire\Components\MyMoney;
use App\Models\Produto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static function update(?string $get, ?string $state, Set $set) : void {

        $state = str_replace(',', '.', $state);
        $get   = str_replace(',', '.', $get);
        dump("get" , $get);
        if ($get === null){
            $get = 1;
        }

        $state = (double) $get * (double) $state ;

        $set('total', number_format((float) $state, 2, ',', '.'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Forms\Components\TextInput::make('descricao_curta')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                MyMoney::make('preco')
                    ->required()
                    ->columnSpan(3)
                    ->inputMode('decimal')
                    ->afterStateUpdated(fn(Get $get, ?string $state, Set $set)  => Self::update($get('quantidade'), $state, $set))
                    ->live()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantidade')
                    ->integer()
                    ->columnSpan(3)
                    ->inputMode('decimal')
                    ->afterStateUpdated(fn(Get $get, ?string $state,  Set $set) =>  Self::update($get('preco'), $state, $set))
                    ->live()
                    ->required(),
                Forms\Components\Select::make('tipo_medida')
                    ->options([
                        'unidade' => 'unidade',
                        'kg' => 'kg',
                    ])
                    ->searchable()
                    ->columnSpan(3)
                    ->required()
                    ->native(true),
                Forms\Components\TextInput::make('total')
                    ->columnSpan(3)
                    ->readOnly(),
                Forms\Components\DatePicker::make('data_compra')
                    ->columnSpan(6)
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->columnSpan(6)
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->whereId(Auth::user()->getAuthIdentifier())
                    )
                    ->native(condition: true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao_curta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preco')
                    ->money(currency: 'BRL', locale: 'pt_BR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantidade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->money(currency: 'BRL', locale: 'pt_BR'),
                Tables\Columns\TextColumn::make('tipo_medida')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_compra')
                    ->date()
                    ->sortable(),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'view' => Pages\ViewProduto::route('/{record}'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }
}
