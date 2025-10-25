<?php

declare(strict_types=1);

use function Livewire\Volt\{state , layout};

layout('layouts.guest');

if (!state('total') instanceof \Livewire\Volt\Options\StateOptions) {
    state('total', 0);
}

?>

<div>
    <h1>Hello word</h1>
</div>
