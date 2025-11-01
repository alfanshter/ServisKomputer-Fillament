<?php

use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/print/tanda-terima/{id}', [PrintController::class, 'tandaTerima'])
    ->name('print.tanda-terima');