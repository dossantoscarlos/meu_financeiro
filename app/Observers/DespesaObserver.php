<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Despesa;
use App\Traits\ControleCusto;

class DespesaObserver
{
    use ControleCusto;

    /**
     * Handle the Despesa "created" event.
     */
    public function saved(Despesa $despesa): void
    {
        $this->controleCusto();
    }

    /**
     * Handle the Despesa "updated" event.
     */
    public function updated(Despesa $despesa): void
    {
        $this->controleCusto();
    }

    /**
     * Handle the Despesa "deleted" event.
     */
    public function deleted(Despesa $despesa): void
    {
        $this->controleCusto();
    }

    /**
     * Handle the Despesa "restored" event.
     */
    public function restored(Despesa $despesa): void
    {
        //
    }

    /**
     * Handle the Despesa "force deleted" event.
     */
    public function forceDeleted(Despesa $despesa): void
    {
        //
    }
}
