<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

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

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.connections.sqlite.database' => 'testing']);

        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_can_render_page(): void
    {
        $this->get(DespesaResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_can_list_records(): void
    {
        $plano = Plano::factory()->create(['user_id' => auth()->id()]);
        $despesas = Despesa::factory()->count(5)->create(['plano_id' => $plano->id]);

        Livewire::test(DespesaResource\Pages\ManageDespesas::class)
            ->assertCanSeeTableRecords($despesas);
    }

    public function test_can_create_record(): void
    {
        $status = StatusDespesa::factory()->create();
        $plano = Plano::factory()->create(['user_id' => auth()->id()]);
        $tipo = TipoDespesa::factory()->create();

        $form = [
            'descricao' => 'Nova Despesa',
            'data_vencimento' => now()->toDateString(),
            'plano_id' => $plano->id,
            'status_despesa_id' => $status->id,
            'tipo_despesa_id' => $tipo->id,
            'valor_documento' => '100',
        ];

        $keys = array_keys($form);

        Livewire::test(DespesaResource\Pages\ManageDespesas::class)
            ->mountAction(CreateAction::class)
            ->fillForm($form)
            ->callMountedAction()
            ->assertHasNoFormErrors($keys);

        $this->assertDatabaseHas('despesas', $form);
    }
}
