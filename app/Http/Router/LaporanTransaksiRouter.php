<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'laporan-transaksi',
    'as' => 'laporanTransaksi.',
    'middleware' => ['auth:sanctum', 'verified']
], function () {
    Route::get('/', \App\Http\Livewire\LaporanTransaksi\Index::class)
        ->name('index');
});
