<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Widgets;

use App\Filament\Widgets\ProdutoTable;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProdutoTableTest extends TestCase
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
        $produtos = Produto::factory()->count(3)->create(['user_id' => $this->user->id]);

        Livewire::test(ProdutoTable::class)
            ->assertCanSeeTableRecords($produtos);
    }
}
