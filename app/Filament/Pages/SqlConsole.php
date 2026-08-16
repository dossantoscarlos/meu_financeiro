<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Throwable;

class SqlConsole extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $navigationLabel = 'Console SQL';

    protected static ?string $title = 'Executar Query SQL Direta';

    protected static string|\UnitEnum|null $navigationGroup = 'Ferramentas';

    protected string $view = 'filament.pages.sql-console';

    public string $sql = '';

    /** @var array<int, array<string, mixed>>|null */
    public ?array $results = null;

    /** @var array<int, string>|null */
    public ?array $columns = null;

    public ?int $affectedRows = null;

    public ?float $executionTime = null;

    public ?string $errorMessage = null;

    public ?string $queryType = null;

    public function mount(): void
    {
        if (empty($this->sql)) {
            $this->sql = 'SELECT * FROM users LIMIT 10;';
        }
    }

    public function runQuery(): void
    {
        $this->resetResults();

        $query = trim($this->sql);

        if ($query === '') {
            $this->errorMessage = 'Por favor, digite um comando SQL válido.';
            return;
        }

        $startTime = microtime(true);

        try {
            $firstWord = strtoupper(strtok($query, " \n\r\t") ?: '');

            $isReadQuery = in_array($firstWord, ['SELECT', 'EXPLAIN', 'SHOW', 'PRAGMA', 'WITH'], true);

            if ($isReadQuery) {
                /** @var array<int, object|array<string, mixed>> $rawResults */
                $rawResults = DB::select($query);

                $this->executionTime = round((microtime(true) - $startTime) * 1000, 2);
                $this->queryType = 'select';

                $formattedResults = [];
                foreach ($rawResults as $row) {
                    $formattedResults[] = (array) $row;
                }

                if (!empty($formattedResults)) {
                    $this->columns = array_keys($formattedResults[0]);
                } else {
                    $this->columns = [];
                }

                $this->results = array_slice($formattedResults, 0, 500);
            } else {
                $affected = DB::affectingStatement($query);
                $this->executionTime = round((microtime(true) - $startTime) * 1000, 2);
                $this->queryType = 'affecting';
                $this->affectedRows = $affected;
            }
        } catch (Throwable $e) {
            $this->executionTime = round((microtime(true) - $startTime) * 1000, 2);
            $this->errorMessage = $e->getMessage();
        }
    }

    public function loadPreset(string $presetSql): void
    {
        $this->sql = $presetSql;
        $this->runQuery();
    }

    public function resetResults(): void
    {
        $this->results = null;
        $this->columns = null;
        $this->affectedRows = null;
        $this->executionTime = null;
        $this->errorMessage = null;
        $this->queryType = null;
    }
}
