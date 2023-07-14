<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'admin'])->group(function() {

    // Rutas Especialidades
Route::get('/servicios', [App\Http\Controllers\admin\ServicesController::class, 'index']);
Route::get('/servicios/create', [App\Http\Controllers\admin\ServicesController::class, 'create']);
Route::get('/servicios/{services}/edit', [App\Http\Controllers\admin\ServicesController::class, 'edit']);
Route::post('/servicios', [App\Http\Controllers\admin\ServicesController::class, 'sendData']);
Route::put('/servicios/{services}', [App\Http\Controllers\admin\ServicesController::class, 'update']);
Route::delete('/servicios/{services}', [App\Http\Controllers\admin\ServicesController::class, 'destroy']);

//Ruta Nosotros
Route::resource('nosotros', 'App\Http\Controllers\admin\UsController');

// Ruta Pacientes
Route::resource('pacientes', 'App\Http\Controllers\admin\PatientController');


//Ruta Reportes
Route::get('/reportes/citas/line', [App\Http\Controllers\admin\ChartController::class, 'appointments']);
Route::get('/reportes/especialistas/column', [App\Http\Controllers\admin\ChartController::class, 'especialistas']);

});

Route::middleware(['auth', 'colaboradores'])->group(function() {

    Route::get('/horario', [App\Http\Controllers\Employee\HorarioController::class, 'edit']);
    Route::post('/horario', [App\Http\Controllers\Employee\HorarioController::class, 'store']);


});

Route::middleware('auth')->group(function() {

    Route::get('/reservarcitas/create', [App\Http\Controllers\AppointmentController::class, 'create']);
    Route::post('/reservarcitas', [App\Http\Controllers\AppointmentController::class, 'store']);
    Route::get('/miscitas', [App\Http\Controllers\AppointmentController::class, 'index']);
    Route::get('/miscitas/{appointment}', [App\Http\Controllers\AppointmentController::class, 'show']);
    Route::post('/miscitas/{appointment}/cancel', [App\Http\Controllers\AppointmentController::class, 'cancel']);
    Route::post('/miscitas/{appointment}/confirm', [App\Http\Controllers\AppointmentController::class, 'confirm']);
    
    Route::get('/miscitas/{appointment}/cancel', [App\Http\Controllers\AppointmentController::class, 'formCancel']);

    Route::get('/profile', [App\Http\Controllers\UserController::class, 'edit']);
    Route::post('/profile', [App\Http\Controllers\UserController::class, 'update']);
    


});
    

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');