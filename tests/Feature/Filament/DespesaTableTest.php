<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

// use App\Enums\StatusDespesaEnum; removed
use App\Filament\Resources\DespesaResource;
use App\Models\Despesa;
use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DespesaTableTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([1 => 'pendente', 2 => 'atrasado', 3 => 'pago'] as $id => $nome) {
            StatusDespesa::updateOrCreate(
                ['id' => $id],
                ['nome' => $nome]
            );
        }

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_table_can_filter_by_default_pendente_and_atrasado(): void
    {
        $plano = Plano::factory()->create(['user_id' => $this->user->id]);

        $pendente = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::PENDENTE,
            'descricao' => 'Despesa Pendente',
        ]);

        $atrasada = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::ATRASADO,
            'descricao' => 'Despesa Atrasada',
        ]);

        $paga = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::PAGO,
            'descricao' => 'Despesa Paga',
        ]);

        Livewire::test(DespesaResource\Pages\ManageDespesas::class)
            ->assertCanSeeTableRecords([$pendente, $atrasada])
            ->assertCanNotSeeTableRecords([$paga]);
    }

    public function test_table_can_filter_by_pago(): void
    {
        $plano = Plano::factory()->create(['user_id' => $this->user->id]);

        $pendente = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::PENDENTE,
            'descricao' => 'Despesa Pendente',
        ]);

        $paga = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::PAGO,
            'descricao' => 'Despesa Paga',
        ]);

        Livewire::test(DespesaResource\Pages\ManageDespesas::class)
            ->filterTable('status_despesa_id', [StatusDespesa::PAGO])
            ->assertCanSeeTableRecords([$paga])
            ->assertCanNotSeeTableRecords([$pendente]);
    }

    public function test_table_can_filter_by_pendente_separately(): void
    {
        $plano = Plano::factory()->create(['user_id' => $this->user->id]);

        $pendente = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::PENDENTE,
            'descricao' => 'Despesa Pendente',
        ]);

        $atrasada = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::ATRASADO,
            'descricao' => 'Despesa Atrasada',
        ]);

        Livewire::test(DespesaResource\Pages\ManageDespesas::class)
            ->filterTable('status_despesa_id', [StatusDespesa::PENDENTE])
            ->assertCanSeeTableRecords([$pendente])
            ->assertCanNotSeeTableRecords([$atrasada]);
    }

    public function test_table_can_filter_by_atrasado_separately(): void
    {
        $plano = Plano::factory()->create(['user_id' => $this->user->id]);

        $pendente = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::PENDENTE,
            'descricao' => 'Despesa Pendente',
        ]);

        $atrasada = Despesa::factory()->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::ATRASADO,
            'descricao' => 'Despesa Atrasada',
        ]);

        Livewire::test(DespesaResource\Pages\ManageDespesas::class)
            ->filterTable('status_despesa_id', [StatusDespesa::ATRASADO])
            ->assertCanSeeTableRecords([$atrasada])
            ->assertCanNotSeeTableRecords([$pendente]);
    }
}
