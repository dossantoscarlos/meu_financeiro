<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Gasto;
use App\Models\Plano;
use Tests\TestCase;

class GastoModelTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_gastos(): void
    {

        $plano = Plano::factory()->create();

        $gasto = Gasto::create([
            'plano_id' => $plano->id,
            'valor' => 100,
        ]);

        $this->assertDatabaseHas('gastos', [
            'plano_id' => $plano->id,
            'valor' => 100,
        ]);
    }
}
