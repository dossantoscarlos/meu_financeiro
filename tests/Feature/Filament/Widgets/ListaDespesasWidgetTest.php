<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Widgets\ListaDespesasWidget;
use App\Models\Despesa;
use App\Models\Plano;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListaDespesasWidgetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_can_render_widget(): void
    {
        $plano = Plano::factory()->create(['user_id' => auth()->id(), 'mes_ano' => now()->format('m/Y')]);
        $status = \App\Models\StatusDespesa::factory()->create(['nome' => 'pendente']);
        $despesas = Despesa::factory()->count(3)->create([
            'plano_id' => $plano->id,
            'status_despesa_id' => $status->id
        ]);

        Livewire::test(ListaDespesasWidget::class)
            ->assertCanSeeTableRecords($despesas);
    }

    public function test_only_shows_user_despesas(): void
    {
        $otherUser = User::factory()->create();
        $otherPlano = Plano::factory()->create(['user_id' => $otherUser->id]);
        $otherDespesa = Despesa::factory()->create(['plano_id' => $otherPlano->id]);

        $status = \App\Models\StatusDespesa::factory()->create(['nome' => 'pendente']);
        $myPlano = Plano::factory()->create(['user_id' => auth()->id(), 'mes_ano' => now()->format('m/Y')]);
        $myDespesa = Despesa::factory()->create([
            'plano_id' => $myPlano->id,
            'status_despesa_id' => $status->id
        ]);

        Livewire::test(ListaDespesasWidget::class)
            ->assertCanSeeTableRecords([$myDespesa])
            ->assertCanNotSeeTableRecords([$otherDespesa]);
    }
}
