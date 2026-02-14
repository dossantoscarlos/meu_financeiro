<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\PlanoResource;
use App\Models\Plano;
use App\Models\User;
use Filament\Actions\CreateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PlanoResourceTest extends TestCase
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
        $this->get(PlanoResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_can_list_records(): void
    {
        $planos = Plano::factory()->count(5)->create(['user_id' => $this->user->id]);

        Livewire::test(PlanoResource\Pages\ManagePlanos::class)
            ->assertCanSeeTableRecords($planos);
    }

    public function test_can_create_record(): void
    {
        Livewire::test(PlanoResource\Pages\ManagePlanos::class)
            ->mountAction(CreateAction::class)
            ->fillForm([
                'user_id' => $this->user->id,
                'mes_ano' => '12/2025',
                'descricao_simples' => 'Plano Dezembro',
            ])
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('planos', [
            'mes_ano' => '12/2025',
            'user_id' => $this->user->id,
        ]);
    }
}
