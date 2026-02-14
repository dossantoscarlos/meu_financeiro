<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Widgets\ListaDespesasWidget;
use App\Models\Despesa;
use App\Models\Plano;
use App\Models\StatusDespesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListaDespesasFilteringTest extends TestCase
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

    public function test_it_filters_despesas_correctly(): void
    {
        $now = \Illuminate\Support\Facades\Date::now();
        $currentMesAno = $now->format('m/Y');
        $pastMesAno = $now->copy()->subMonth()->format('m/Y');
        $futureMesAno = $now->copy()->addMonth()->format('m/Y');

        $user = User::factory()->create();

        $currentPlano = Plano::factory()->create(['user_id' => $user->id, 'mes_ano' => $currentMesAno]);
        $pastPlano = Plano::factory()->create(['user_id' => $user->id, 'mes_ano' => $pastMesAno]);
        $futurePlano = Plano::factory()->create(['user_id' => $user->id, 'mes_ano' => $futureMesAno]);

        Despesa::factory()->create(['plano_id' => $currentPlano->id, 'status_despesa_id' => 1, 'descricao' => 'Current Pendente']);
        Despesa::factory()->create(['plano_id' => $pastPlano->id, 'status_despesa_id' => 3, 'descricao' => 'Past Pago']);
        Despesa::factory()->create(['plano_id' => $pastPlano->id, 'status_despesa_id' => 1, 'descricao' => 'Past Pendente']);
        Despesa::factory()->create(['plano_id' => $futurePlano->id, 'status_despesa_id' => 3, 'descricao' => 'Future Pago']);

        Livewire::actingAs($user)
            ->test(ListaDespesasWidget::class)
            ->assertSee('Current Pendente')
            ->assertSee('Past Pendente')
            ->assertDontSee('Past Pago')
            ->assertDontSee('Future Pago');
    }
}
