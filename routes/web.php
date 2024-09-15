<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\OrdenController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //hamburguesada
        //ordenes
        Route::get('/ordenes/index', [OrdenController::class, 'index'])->name('ordenes.index');
        Route::get('/ordenes/crear', [OrdenController::class, 'create'])->name('ordenes.create');
        Route::post('/ordenes', [OrdenController::class, 'store'])->name('ordenes.store');
        Route::get('/ordenes/{id}', [OrdenController::class, 'show'])->name('ordenes.show');
        Route::get('/ordenes/detalle/realizados/{id}', [OrdenController::class, 'showRealizado'])->name('ordenes.showRealizado');
        Route::get('/ordenes/{id}/terminar', [OrdenController::class, 'terminar'])->name('ordenes.finish');
        Route::put('/ordenes/{id}', [OrdenController::class, 'update']);
        Route::put('/ordenes/editar/zona/{id}', [OrdenController::class, 'updateZona'])->name('ordenes.updateZona');
        Route::get('/ordenes/{id}/editar', [OrdenController::class, 'edit'])->name('ordenes.edit');
        Route::get('/ordenes/pedidos/realizados', [OrdenController::class, 'realizados'])->name('ordenes.realizados');
        Route::get('/ordenes/envios/realizados', [OrdenController::class, 'enviosRealizados'])->name('ordenes.enviosRealizados');
        Route::get('/ordenes/envios/pendientes', [OrdenController::class, 'enviosPendientes'])->name('ordenes.enviosPendientes');
        Route::put('/ordenes/editar/repartidor/{id}', [OrdenController::class, 'editarRepartidor'])->name('ordenes.editarRepartidor');
        Route::put('/ordenes/entregar/{orden}', [OrdenController::class, 'entregar']);
        Route::put('/ordenes/no/entregar/{orden}', [OrdenController::class, 'noEntregar']);
        Route::get('/ordenes/envios/pendientes/show/{id}', [OrdenController::class, 'showPendiente'])->name('ordenes.showPendiente');
        Route::get('/ordenes/envios/realizados/show/{id}', [OrdenController::class, 'showPendiente'])->name('ordenes.showRealizados');
            //detalles
            Route::post('/detalles_orden', [\App\Http\Controllers\DetalleOrdenController::class, 'store'])->name('detalles_orden.store');
});

require __DIR__.'/auth.php';
