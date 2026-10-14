<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Prototipo visual (solo en local)
|--------------------------------------------------------------------------
| Pantallas con datos falsos para validar el diseño sin BD de dominio ni login.
| No existen en testing ni en producción. Para quitarlo todo: borrar este bloque,
| resources/views/prototype/ y resources/views/pages/prototype/.
*/
if (app()->isLocal()) {
    // Los datos falsos se pasan a las vistas como lo haría un controlador con Eloquent.
    $fake = require resource_path('views/prototype/_fake-data.php');
    $vehicleIds = array_map('strval', array_keys($fake['vehicles']));

    Route::prefix('prototipo')->name('prototype.')->group(function () use ($fake, $vehicleIds) {
        Route::view('/', 'prototype.index', $fake)->name('index');
        Route::view('estilo', 'prototype.style', $fake)->name('style');

        Route::prefix('acceso')->name('auth.')->group(function () use ($fake) {
            Route::view('entrar', 'prototype.auth.login', $fake)->name('login');
            Route::view('registro', 'prototype.auth.register', $fake)->name('register');
            Route::view('olvido', 'prototype.auth.forgot-password', $fake)->name('forgot');
            Route::view('restablecer', 'prototype.auth.reset-password', $fake)->name('reset');
            Route::view('verificar', 'prototype.auth.verify-email', $fake)->name('verify');
        });

        Route::view('baraja', 'prototype.deck', $fake)->name('deck');
        Route::view('vehiculos/nuevo', 'prototype.vehicle-form', [...$fake, 'mode' => 'create'])->name('vehicles.create');
        Route::view('vehiculos/{vehicle}', 'prototype.vehicle', $fake)->whereIn('vehicle', $vehicleIds)->name('vehicles.show');
        Route::view('vehiculos/{vehicle}/editar', 'prototype.vehicle-form', [...$fake, 'mode' => 'edit'])->whereIn('vehicle', $vehicleIds)->name('vehicles.edit');
        Route::view('vehiculos/{vehicle}/historial', 'prototype.vehicle-history', $fake)->whereIn('vehicle', $vehicleIds)->name('vehicles.history');
        Route::view('rituales', 'prototype.rituals', $fake)->name('rituals');
        Route::view('logros', 'prototype.achievements', $fake)->name('achievements');
        Route::livewire('oraculo', 'pages::prototype.oracle')->name('oracle');
        Route::view('admin', 'prototype.admin', $fake)->name('admin');

        Route::prefix('ajustes')->name('settings.')->group(function () use ($fake) {
            Route::view('perfil', 'prototype.settings.profile', $fake)->name('profile');
            Route::view('seguridad', 'prototype.settings.security', $fake)->name('security');
            Route::view('apariencia', 'prototype.settings.appearance', $fake)->name('appearance');
        });

        Route::view('errores/403', 'errors.403')->name('errors.403');
        Route::view('errores/404', 'errors.404')->name('errors.404');
        Route::view('errores/500', 'errors.500')->name('errors.500');
    });
}

require __DIR__.'/settings.php';
