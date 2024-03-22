<?php

namespace App\Observers;

use App\Models\Dispesa;
use App\Models\Plano;
use App\Models\Receita;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DispesaObserver
{
    /**
     * Handle the Dispesa "created" event.
     */
    public function saved(Dispesa $dispesa): void
    {
        $authId = Auth::user()->getAuthIdentifier();
        $receita = Receita::whereUserId($authId)->first();
        $date = Carbon::now();
        $mes = ($date->month >= 1 && $date->month <= 9) ? strval("0".$date->month) : $date->month  ;
        $mesAno = "{$mes}/{$date->year}";
        
        $dispesas = Plano::with('dispesa')->where([
                ['user_id', '=', $authId],
                ['mes_ano', '=', $mesAno]
            ])
            ->first()
            ->toArray();

        if (!empty($receita)) { 
            
            $total = 0.0;
            
            if (!empty($dispesas)) {
                foreach($dispesas['dispesa'] as $dispesa) {
                    $total += (float) $dispesa['valor_documento'];
                }

                $receita->custo = strval($receita->saldo - $total);
                $receita->update();
                $receita->save();
            }
        }
    }

    /**
     * Handle the Dispesa "updated" event.
     */
    public function updated(Dispesa $dispesa): void
    {
        //
    }

    /**
     * Handle the Dispesa "deleted" event.
     */
    public function deleted(Dispesa $dispesa): void
    {
        //
    }

    /**
     * Handle the Dispesa "restored" event.
     */
    public function restored(Dispesa $dispesa): void
    {
        //
    }

    /**
     * Handle the Dispesa "force deleted" event.
     */
    public function forceDeleted(Dispesa $dispesa): void
    {
        //
    }
}
