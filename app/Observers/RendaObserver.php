<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Renda;

class RendaObserver
{
    /**
     * Handle the Receita "created" event.
     */
    public function created(Renda $renda): void
    {
        $renda->custo = $renda->saldo;
        $renda->update();
    }

    /**
     * Handle the Receita "updated" event.
     */
    public function updated(Renda $renda): void
    {

    }

    /**
     * Handle the Renda "deleted" event.
     */
    public function deleted(Renda $renda): void
    {

    }

    /**
     * Handle the Renda "restored" event.
     */
    public function restored(Renda $renda): void
    {
        //
    }

    /**
     * Handle the Renda "force deleted" event.
     */
    public function forceDeleted(Renda $renda): void
    {
        //
    }
}
