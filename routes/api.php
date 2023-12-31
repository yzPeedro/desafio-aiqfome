<?php

use App\Http\Controllers\Api\PessoasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('/pessoas', PessoasController::class)->except(['update', 'destroy']);
Route::get('/contagem-pessoas', [PessoasController::class, 'count'])->name('pessoas.count');
