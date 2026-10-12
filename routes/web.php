<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComisionController;
Route::redirect('/', '/comisiones');
Route::get('/comisiones', [ComisionController::class, 'index'])->name('comisiones.index');
Route::post('/comisiones', [ComisionController::class, 'calcular'])->name('comisiones.calcular');
