<?php

use Illuminate\Support\Facades\Route;

/**
 * El sistema es solo el panel de TI: la raiz manda al login de Filament.
 * Si ya hay sesion iniciada, Filament redirige al dashboard por su cuenta.
 */
Route::get('/', fn () => redirect()->route('filament.admin.auth.login'))->name('home');
