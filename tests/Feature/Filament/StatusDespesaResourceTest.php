<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\StatusDespesaResource;
use App\Models\StatusDespesa;
use App\Models\User;
use Filament\Actions\CreateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StatusDespesaResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_render_page(): void
    {
        $this->get(StatusDespesaResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_can_list_records(): void
    {
        $status = StatusDespesa::factory()->count(5)->create();

        Livewire::test(StatusDespesaResource\Pages\ManageStatusDespesas::class)
            ->assertCanSeeTableRecords($status);
    }

    public function test_can_create_record(): void
    {
        Livewire::test(StatusDespesaResource\Pages\ManageStatusDespesas::class)
            ->mountAction(CreateAction::class)
            ->fillForm([
                'nome' => 'Novo Status',
            ])
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('status_despesas', [
            'nome' => 'Novo Status',
        ]);
    }
}
