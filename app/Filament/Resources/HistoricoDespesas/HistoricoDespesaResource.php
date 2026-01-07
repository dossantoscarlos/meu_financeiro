<?php

declare(strict_types=1);

namespace App\Filament\Resources\HistoricoDespesas;

use App\Filament\Resources\HistoricoDespesas\Pages\ListHistoricoDespesas;
use App\Filament\Resources\HistoricoDespesas\Schemas\HistoricoDespesaForm;
use App\Filament\Resources\HistoricoDespesas\Schemas\HistoricoDespesaInfolist;
use App\Filament\Resources\HistoricoDespesas\Tables\HistoricoDespesasTable;
use App\Models\HistoricoDespesa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class HistoricoDespesaResource extends Resource
{
    protected static ?string $model = HistoricoDespesa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'Relatórios';


    public static function form(Schema $schema): Schema
    {
        return HistoricoDespesaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HistoricoDespesaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistoricoDespesasTable::configure($table);
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
            'index' => ListHistoricoDespesas::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->join('despesas', 'despesas.id', '=', 'historico_despesas.despesa_id')
            ->join('planos', 'planos.id', '=', 'despesas.plano_id')
            ->where('planos.user_id', Auth::id())
            ->whereNull('despesas.deleted_at')
            ->whereNull('planos.deleted_at')
            ->distinct()
            ->select('historico_despesas.*');
    }
}
