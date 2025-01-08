<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect('/admin/register');
});

// Volt::route('/home', 'home');
