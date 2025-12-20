<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\ProdutoResource;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProdutoResourceTest extends TestCase
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
        $this->get(ProdutoResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_can_list_records(): void
    {
        $produtos = Produto::factory()->count(5)->create(['user_id' => auth()->id()]);

        Livewire::test(ProdutoResource\Pages\ListProdutos::class)
            ->assertCanSeeTableRecords($produtos);
    }

    public function test_can_render_create_page(): void
    {
        $this->get(ProdutoResource::getUrl('create'))
            ->assertSuccessful();
    }

    public function test_can_create_record(): void
    {
        Livewire::test(ProdutoResource\Pages\CreateProduto::class)
            ->fillForm([
                'descricao_curta' => 'Novo Produto',
                'preco' => '10,00',
                'quantidade' => '5',
                'tipo_medida' => 'unidade',
                'data_compra' => now()->toDateString(),
                'user_id' => auth()->id(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('produtos', [
            'descricao_curta' => 'Novo Produto',
            'user_id' => auth()->id(),
        ]);
    }
}
