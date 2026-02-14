<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Widgets\CustoWidget;
use App\Models\Plano;
use App\Models\Renda;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustoWidgetTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_render_widget(): void
    {
        Renda::factory()->create(['user_id' => $this->user->id, 'saldo' => 5000, 'custo' => 0]);
        Plano::factory()->create(['user_id' => $this->user->id, 'mes_ano' => now()->format('m/Y')]);

        Livewire::test(CustoWidget::class)
            ->assertSuccessful()
            ->assertSee('Renda Inicial')
            ->assertSee('Custo previsto')
            ->assertSee('Renda atual');
    }
}
