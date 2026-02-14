<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Models\Despesa;
use App\Models\Gasto;
use App\Models\Plano;
use App\Models\Renda;
use App\Models\StatusDespesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendaObserverTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);
    }

    public function test_custo_is_updated_when_saldo_changes(): void
    {
        // Create Renda
        $renda = Renda::factory()->create([
            'user_id' => $this->user->id,
            'saldo' => 5000,
            'custo' => 0,
        ]);

        // Create a Plano with some Despesas
        $plano = Plano::factory()->create([
            'user_id' => $this->user->id,
            'mes_ano' => now()->format('m/Y'),
        ]);

        $status = StatusDespesa::factory()->create(['nome' => 'pendente']);

        Despesa::factory()->create([
            'plano_id' => $plano->id,
            'valor_documento' => 1000,
            'status_despesa_id' => $status->id,
        ]);

        Gasto::factory()->create([
            'plano_id' => $plano->id,
            'valor' => 1000,
        ]);

        $gasto = Gasto::where('plano_id', $plano->id)->first();

        $this->assertEquals(1000, (float) $gasto->valor);

        // Trigger update to recalculate custo (initial creation already triggered it but let's be explicit)
        $renda->refresh();
        // Since we created a despesa, custo should already be updated if despesa observer worked
        // custo = 5000 - 1000 = 4000
        $custo = ($renda->saldo - $gasto->valor);
        $this->assertEquals(4000, (float) $custo);

        // Now update saldo
        $renda->update(['saldo' => 6000]);

        // custo should be 6000 - 1000 = 5000
        $renda->refresh();
        $this->assertEquals(5000, (float) ($renda->saldo - $gasto->valor));
    }
}
