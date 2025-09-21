<?php

use App\Http\Livewire\GruposEconomicos;
use App\Http\Livewire\Bandeiras;
use App\Http\Livewire\Unidades;
use App\Http\Livewire\Colaboradores;
use App\Http\Livewire\RelatorioColaboradores;
use Illuminate\Support\Facades\Route;

// Rotas de Autenticação MANUALMENTE
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Se você precisar de registro (cadastro de usuários)
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Rotas PROTEGIDAS (exigem login)
Route::middleware(['auth'])->group(function () {
    Route::get('/grupos-economicos', GruposEconomicos::class)->name('grupos-economicos');
    Route::get('/bandeiras', Bandeiras::class)->name('bandeiras');
    Route::get('/unidades', Unidades::class)->name('unidades');
    Route::get('/colaboradores', Colaboradores::class)->name('colaboradores');
    Route::get('/relatorio-colaboradores', RelatorioColaboradores::class)->name('relatorio-colaboradores');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

// Rota raiz
Route::get('/', function () {
    return auth()->check() ? redirect('/home') : redirect('/login');
});