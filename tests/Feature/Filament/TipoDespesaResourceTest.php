<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\TipoDespesaResource;
use App\Filament\Resources\TipoDespesaResource\Pages\ManageTipoDespesas;
use App\Models\TipoDespesa;
use App\Models\User;
use Filament\Actions\CreateAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TipoDespesaResourceTest extends TestCase
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
        $url = TipoDespesaResource::getUrl('index');
        $this->get($url)
            ->assertSuccessful();
    }

    public function test_can_list_records(): void
    {
        $records = TipoDespesa::factory()->count(10)->create();

        Livewire::test(ManageTipoDespesas::class)
            ->assertCanSeeTableRecords($records);
    }

    public function test_can_create_record(): void
    {
        $newData = TipoDespesa::factory()->make();

        Livewire::test(ManageTipoDespesas::class)
            ->callAction(CreateAction::class, data: [
                'nome' => $newData->nome,
            ])
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tipo_despesas', [
            'nome' => mb_strtoupper((string) $newData->nome),
        ]);
    }

    public function test_name_is_required(): void
    {
        Livewire::test(ManageTipoDespesas::class)
            ->callAction(CreateAction::class, data: [
                'nome' => null,
            ])
            ->assertHasFormErrors(['nome' => ['required']]);
    }

    public function test_name_is_unique(): void
    {
        $existingRecord = TipoDespesa::factory()->create(['nome' => 'TESTE']);

        Livewire::test(ManageTipoDespesas::class)
            ->callAction(CreateAction::class, data: [
                'nome' => $existingRecord->nome,
            ])
            ->assertHasFormErrors(['nome' => ['unique']]);
    }
}
