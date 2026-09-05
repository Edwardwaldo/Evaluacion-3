<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProyectoApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Eval. 3
|--------------------------------------------------------------------------
| Todas las rutas aqui declaradas quedan automaticamente bajo el prefijo
| /api y el grupo de middleware "api" (definido en bootstrap/app.php).
| Ejemplo: Route::get('/proyectos', ...) -> GET /api/proyectos
*/

Route::get('/proyectos', [ProyectoApiController::class, 'index']);
Route::post('/proyectos', [ProyectoApiController::class, 'store']);

Route::get('/proyectos/{id}', [ProyectoApiController::class, 'show'])->whereNumber('id');
Route::put('/proyectos/{id}', [ProyectoApiController::class, 'update'])->whereNumber('id');
Route::patch('/proyectos/{id}', [ProyectoApiController::class, 'update'])->whereNumber('id');
Route::delete('/proyectos/{id}', [ProyectoApiController::class, 'destroy'])->whereNumber('id');
