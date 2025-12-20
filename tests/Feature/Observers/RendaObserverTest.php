<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Models\Despesa;
use App\Models\Plano;
use App\Models\Renda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendaObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_custo_is_updated_when_saldo_changes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create Renda
        $renda = Renda::factory()->create([
            'user_id' => $user->id,
            'saldo' => 5000,
            'custo' => 5000,
        ]);

        // Create a Plano with some Despesas
        $plano = Plano::factory()->create([
            'user_id' => $user->id,
            'mes_ano' => now()->format('m/Y'),
        ]);

        Despesa::factory()->create([
            'plano_id' => $plano->id,
            'valor_documento' => 1000,
        ]);

        // Trigger update to recalculate custo (initial creation already triggered it but let's be explicit)
        $renda->refresh();
        // Since we created a despesa, custo should already be updated if despesa observer worked
        // custo = 5000 - 1000 = 4000
        $this->assertEquals(4000, (float) $renda->custo);

        // Now update saldo
        $renda->update(['saldo' => 6000]);

        // custo should be 6000 - 1000 = 5000
        $renda->refresh();
        $this->assertEquals(5000, (float) $renda->custo);
    }
}
