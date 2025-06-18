<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Forms\TimeFormsController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/time/store', [TimeFormsController::class, 'store'])->name('time.store');

Route::get('dashboard', function () {
    return view('dashboard', [
        'idList' => [1,2,3]
       ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
