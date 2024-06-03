<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(Cliente::class)
            ->assertStatus(200);
    }
}
