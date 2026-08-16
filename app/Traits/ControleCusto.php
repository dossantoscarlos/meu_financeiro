<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Despesa;
use App\Models\Gasto;
use App\Models\Plano;
use App\Models\Renda;
use App\Models\StatusDespesa;
use Illuminate\Support\Facades\Auth;

trait ControleCusto
{
    private function controleCusto(?Plano $plano = null, ?int $userId = null): void
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable|null|\App\Models\User $user */
        $user = Auth::user();

        /** @var int|null $authId */
        $authId = $userId ?? $user?->getAuthIdentifier();

        if ($plano && !$authId) {
            $authId = $plano->user_id;
        }

        if (!$authId) {
            return;
        }

        if (!$plano) {
            $plano = Plano::query()->where('user_id', '=', $authId)->latest('created_at')->first();
        }

        if (!$plano) {
            return;
        }

        /** @var float $total */
        $total = (float) Despesa::query()
            ->where('plano_id', '=', $plano->id)
            ->whereIn('status_despesa_id', [StatusDespesa::PENDENTE, StatusDespesa::ATRASADO])
            ->sum('valor_documento');

        Gasto::query()->updateOrCreate(
            ['plano_id' => $plano->id],
            ['valor' => (string) $total]
        );

        $renda = Renda::query()->where('user_id', '=', $authId)->first();
        if ($renda && (float) $renda->custo !== $total) {
            $renda->updateQuietly(['custo' => $total]);
        }
    }
}
