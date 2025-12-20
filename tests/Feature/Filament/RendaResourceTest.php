<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\RendaResource;
use App\Models\Renda;
use App\Models\User;
use Filament\Actions\CreateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RendaResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_can_render_page(): void
    {
        $this->get(RendaResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_can_list_records(): void
    {
        $rendas = Renda::factory()->count(5)->create(['user_id' => auth()->id()]);

        Livewire::test(RendaResource\Pages\ManageRendas::class)
            ->assertCanSeeTableRecords($rendas);
    }

    public function test_can_create_record(): void
    {
        Livewire::test(RendaResource\Pages\ManageRendas::class)
            ->mountAction(CreateAction::class)
            ->setActionData([
                'user_id' => auth()->id(),
                'saldo' => '5000,00',
                'custo' => '100,00',
            ])
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('rendas', [
            'user_id' => auth()->id(),
        ]);
    }
}
