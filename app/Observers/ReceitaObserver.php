<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Receita;

class ReceitaObserver
{
    /**
     * Handle the Receita "created" event.
     */
    public function created(Receita $receita): void
    {
        $receita->custo = $receita->saldo;
        $receita->update();
    }

    /**
     * Handle the Receita "updated" event.
     */
    public function updated(Receita $receita): void
    {

    }

    /**
     * Handle the Receita "deleted" event.
     */
    public function deleted(Receita $receita): void
    {

    }

    /**
     * Handle the Receita "restored" event.
     */
    public function restored(Receita $receita): void
    {
        //
    }

    /**
     * Handle the Receita "force deleted" event.
     */
    public function forceDeleted(Receita $receita): void
    {
        //
    }
}
