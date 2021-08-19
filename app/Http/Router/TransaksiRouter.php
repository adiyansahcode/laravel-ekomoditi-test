<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'transaksi',
    'as' => 'transaksi.',
    'middleware' => ['auth:sanctum', 'verified']
], function () {
    Route::get('/', \App\Http\Livewire\Transaksi\Index::class)
        ->name('index');
});
