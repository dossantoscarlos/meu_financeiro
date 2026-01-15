<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Despesa;
use App\Traits\ControleCusto;
use App\Traits\HistoricoStatusDespesa;

class DespesaObserver
{
    use ControleCusto;
    use HistoricoStatusDespesa;

    /**
     * Handle the Despesa "created" event.
     */
    public function saved(Despesa $despesa): void
    {
        $this->controleCusto($despesa->plano);
        $this->registerHistoricoStatusDespesa($despesa);
    }

    /**
     * Handle the Despesa "updated" event.
     */
    public function updated(Despesa $despesa): void
    {
        $this->controleCusto($despesa->plano);
    }

    /**
     * Handle the Despesa "deleted" event.
     */
    public function deleted(Despesa $despesa): void
    {
        $this->controleCusto($despesa->plano);
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
