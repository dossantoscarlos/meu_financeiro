<?php

use function Livewire\Volt\{state , layout};

layout('layouts.guest');

if(state('total') === null) {
    state('total', 0);
}

?>

<div>
    <h1>Hello word</h1>
</div>
