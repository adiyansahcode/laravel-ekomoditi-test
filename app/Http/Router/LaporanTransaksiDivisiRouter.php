<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'laporan-transaksi-divisi',
    'as' => 'laporanTransaksiDivisi.',
    'middleware' => ['auth:sanctum', 'verified']
], function () {
    Route::get('/', \App\Http\Livewire\LaporanTransaksiDivisi\Index::class)
        ->name('index');
});
