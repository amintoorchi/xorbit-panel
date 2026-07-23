<?php

use App\Http\Controllers\ServerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('servers', 'pages.servers')->name('servers');
    Route::livewire('server/{server}', 'pages.server')->name('server');
});

Route::get('/s', [ServerController::class, 'index']);

require __DIR__.'/settings.php';
