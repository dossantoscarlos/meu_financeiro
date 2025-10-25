<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use Livewire\Volt\Volt;
use Tests\TestCase;

final class HomeTest extends TestCase
{
    public function testItCanRender(): void
    {
        $testable = Volt::test('home');

        $testable->assertSee(true);
    }
}
