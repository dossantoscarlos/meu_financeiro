<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Widgets\ListaCategoria;
use App\Models\TipoDespesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListaCategoriaTest extends TestCase
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
        $categories = TipoDespesa::factory()->count(3)->create();

        Livewire::test(ListaCategoria::class)
            ->assertCanSeeTableRecords($categories);
    }
}
