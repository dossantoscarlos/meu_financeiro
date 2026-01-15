<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\StatusDespesa;
use App\Models\Despesa;
use App\Models\Gasto;
use App\Models\Plano;
use App\Models\Renda;
use Illuminate\Support\Facades\Auth;

trait ControleCusto
{
    private function controleCusto(?Plano $plano = null): void
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable|null|\App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return;
        }

        /** @var int $authId */
        $authId = $user->getAuthIdentifier();

        if (!$plano) {
            $plano = Plano::where([
                ['user_id', $authId],
            ])->latest('created_at')->first();
        }

        if (!$plano) {
            return;
        }

        /** @var float $total */
        $total = 0.0;

        $total = Despesa::whereIn(
            'status_despesa_id',
            [StatusDespesa::PENDENTE, StatusDespesa::ATRASADO]
        )
            ->sum('valor_documento');

        $gasto = Gasto::updateOrCreate(
            ['plano_id' => $plano->id],
            ['valor' => (string) $total]
        );

        $renda = Renda::where('user_id', $authId)->first();
        if ($renda) {
            $renda->update(['custo' => (float) $total]);
        }
    }
}
