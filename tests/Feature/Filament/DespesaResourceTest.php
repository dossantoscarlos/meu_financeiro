<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

// use App\Enums\StatusDespesaEnum; removed
use App\Filament\Resources\DespesaResource;
use App\Models\Despesa;
use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\TipoDespesa;
use App\Models\User;
use Filament\Actions\CreateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DespesaResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.connections.sqlite.database' => 'testing']);

        foreach ([1 => 'pendente', 2 => 'atrasado', 3 => 'pago'] as $id => $nome) {
            StatusDespesa::updateOrCreate(
                ['id' => $id],
                ['nome' => $nome]
            );
        }

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_render_page(): void
    {
        $this->get(DespesaResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_can_list_records(): void
    {
        $plano = Plano::factory()->create(['user_id' => $this->user->id]);
        $despesas = Despesa::factory()->count(5)->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::PENDENTE,
        ]);

        Livewire::test(DespesaResource\Pages\ManageDespesas::class)
            ->assertCanSeeTableRecords($despesas);
    }

    public function test_can_create_record(): void
    {
        $plano = Plano::factory()->create(['user_id' => $this->user->id]);
        $tipo = TipoDespesa::factory()->create();

        $form = [
            'descricao' => 'Nova Despesa',
            'data_vencimento' => now()->toDateString(),
            'plano_id' => $plano->id,
            'status_despesa_id' => StatusDespesa::PENDENTE,
            'tipo_despesa_id' => $tipo->id,
            'valor_documento' => '100',
        ];

        $keys = array_keys($form);

        Livewire::test(DespesaResource\Pages\ManageDespesas::class)
            ->mountAction(CreateAction::class)
            ->fillForm($form)
            ->callMountedAction()
            ->assertHasNoFormErrors($keys);

        $this->assertDatabaseHas('despesas', [
            'descricao' => 'Nova Despesa',
            'status_despesa_id' => StatusDespesa::PENDENTE,
        ]);
    }

    public function test_default_filters_apply_on_load(): void
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
}
