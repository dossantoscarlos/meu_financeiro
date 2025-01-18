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

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static ?string $modelLabel = 'Produto';

    protected static ?string $navigationGroup = 'Operação';

    protected static ?string $pluralModelLabel = 'Produtos';

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static function total_produto(?string $preco, ?string $quantidade): float
    {
        return floatval($preco) * floatval($quantidade);
    }

    protected static function update(?string $get, ?string $state, Set $set): void
    {

        $state = str_replace(',', '.', $state);
        $get = str_replace(',', '.', $get);
        dump('get', $get);
        if ($get === null) {
            $get = 1;
        }

        $state = (float) $get * (float) $state;

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
                    ->afterStateUpdated(
                        fn (Get $get, ?string $state, Set $set) => self::update($get('quantidade'), $state, $set)
                    )
                    ->live()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantidade')
                    ->integer()
                    ->columnSpan(3)
                    ->inputMode('decimal')
                    ->afterStateUpdated(fn (Get $get, ?string $state, Set $set) => self::update($get('preco'), $state, $set))
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
                    ->native(condition: false),
                Forms\Components\TextInput::make('total')
                    ->columnSpan(3)
                    ->prefix('R$')
                    ->inputMode('decimal')
                    ->formatStateUsing(fn (Get $get, ?string $state): ?string => number_format(
                        self::total_produto(
                            $get('preco'),
                            $get('quantidade')
                        ),
                        2,
                        ',',
                        '.'
                    ))
                    ->readOnly(),
                Forms\Components\DatePicker::make('data_compra')
                    ->label('Data da compra')
                    ->columnSpan(6)
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->columnSpan(6)
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->whereId(Auth::user()->getAuthIdentifier())
                    )
                    ->native(condition: false)
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
                    ->label('Data da compra')
                    ->date('d/m/Y')
                    ->sortable(),
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
