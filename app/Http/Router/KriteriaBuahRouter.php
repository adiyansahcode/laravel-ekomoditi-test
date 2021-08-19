<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'kriteria-buah',
    'as' => 'KriteriaBuah.',
    'middleware' => ['auth:sanctum', 'verified']
], function () {
    Route::get('/', \App\Http\Livewire\KriteriaBuah\Index::class)
        ->name('index');
});
