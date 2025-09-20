<?php

use App\Http\Livewire\GruposEconomicos;
use App\Http\Livewire\Bandeiras;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Unidades;
use App\Http\Livewire\Colaboradores;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/grupos-economicos', \App\Http\Livewire\GruposEconomicos::class)->name('grupos-economicos');

Route::get('/bandeiras', Bandeiras::class)->name('bandeiras');

Route::get('/unidades', Unidades::class)->name('unidades');

Route::get('/colaboradores', Colaboradores::class)->name('colaboradores');


