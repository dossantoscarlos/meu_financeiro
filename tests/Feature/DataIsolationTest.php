<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\DespesaResource;
use App\Filament\Resources\PlanoResource;
use App\Filament\Resources\RendaResource;
use App\Models\Despesa;
use App\Models\Plano;
use App\Models\Renda;
use App\Models\StatusDespesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DataIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([1 => 'pendente', 2 => 'atrasado', 3 => 'pago'] as $id => $nome) {
            StatusDespesa::updateOrCreate(
                ['id' => $id],
                ['nome' => $nome]
            );
        }
    }

    public function test_despesa_isolation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $planoA = Plano::factory()->create(['user_id' => $userA->id]);
        $planoB = Plano::factory()->create(['user_id' => $userB->id]);

        $despesaA = Despesa::factory()->create(['plano_id' => $planoA->id, 'status_despesa_id' => 1]);
        $despesaB = Despesa::factory()->create(['plano_id' => $planoB->id, 'status_despesa_id' => 1]);

        Livewire::actingAs($userA)
            ->test(DespesaResource\Pages\ManageDespesas::class)
            ->assertCanSeeTableRecords([$despesaA])
            ->assertCanNotSeeTableRecords([$despesaB]);
    }

    public function test_plano_isolation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $planoA = Plano::factory()->create(['user_id' => $userA->id]);
        $planoB = Plano::factory()->create(['user_id' => $userB->id]);

        Livewire::actingAs($userA)
            ->test(PlanoResource\Pages\ManagePlanos::class)
            ->assertCanSeeTableRecords([$planoA])
            ->assertCanNotSeeTableRecords([$planoB]);
    }

    public function test_renda_isolation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $rendaA = Renda::factory()->create(['user_id' => $userA->id]);
        $rendaB = Renda::factory()->create(['user_id' => $userB->id]);

        Livewire::actingAs($userA)
            ->test(RendaResource\Pages\ManageRendas::class)
            ->assertCanSeeTableRecords([$rendaA])
            ->assertCanNotSeeTableRecords([$rendaB]);
    }
}
