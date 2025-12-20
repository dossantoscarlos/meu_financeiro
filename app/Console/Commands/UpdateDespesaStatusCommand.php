<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\UpdateOverdueDespesasJob;
use Illuminate\Console\Command;

class UpdateDespesaStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-despesa-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch the job to update overdue expense statuses';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Dispatching UpdateOverdueDespesasJob...');
        UpdateOverdueDespesasJob::dispatch();
        $this->info('Job dispatched successfully.');
    }
}
