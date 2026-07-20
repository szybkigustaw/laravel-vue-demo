<?php

use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', 'cards')->name('dashboard');
    Route::resource('cards', CardController::class)->except('show');
});

require __DIR__.'/settings.php';
