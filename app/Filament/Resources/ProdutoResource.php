<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ProdutoResource\Pages;
use App\Livewire\Components\MyMoney;
use App\Models\Produto;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static ?string $modelLabel = 'Produto';

    protected static UnitEnum|string|null $navigationGroup = 'Operação';

    protected static ?string $pluralModelLabel = 'Produtos';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-list-bullet';

    protected static function formatTotal(mixed $preco, mixed $quantidade): string
    {
        $preco = str_replace(',', '.', (string) ($preco ?? '0'));
        $quantidade = str_replace(',', '.', (string) ($quantidade ?? '1'));

        if ($quantidade === null || $quantidade === '') {
            $quantidade = '1';
        }

        $total = floatval($preco) * floatval($quantidade);
        return number_format($total, 2, ',', '.');
    }

    protected static function update(Get $get, ?string $state, Set $set): void
    {
        $set(
            'total',
            self::formatTotal($get('preco'), $get('quantidade'))
        );
    }

    public static function form(Schema $form): Schema
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
                        fn (Get $get, ?string $state, Set $set) =>
                            self::update($get, $state, $set)
                    )
                    ->live()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantidade')
                    ->integer()
                    ->columnSpan(3)
                    ->inputMode('decimal')
                    ->afterStateUpdated(
                        fn (Get $get, ?string $state, Set $set) =>
                            self::update($get, $state, $set)
                    )
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
                    ->formatStateUsing(
                        fn (Get $get): string =>
                        self::formatTotal(
                            $get('preco'),
                            $get('quantidade')
                        )
                    )
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
                        modifyQueryUsing: fn ($query) => $query->where('users.id', Auth::id())
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
                    ->label('Descrição')
                    ->limit(25)
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
            ])->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])->toolbarActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->authorizeIndividualRecords('delete')
                    ->action(fn (Collection $records) => $records->each->delete())
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
