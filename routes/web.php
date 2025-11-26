<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DasLogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\ReferenceController;
use App\Http\Controllers\Master\SensorController;
use App\Http\Controllers\Master\StackController;
use App\Http\Controllers\Master\UnitController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\RcaLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class,'index']);
Route::get('monitoring/{stackId}', [HomeController::class,'index']);
Route::post('monitoring/store', [HomeController::class,'store'])->name("monitoring.rca.store");
Route::get('api/values/{stackId}', [HomeController::class,'values']);

Route::get('das-logs/datatable', [DasLogController::class,'datatable'])->name("das-logs.datatable");
Route::get('das-logs/export', [DasLogController::class,'export'])->name("das-logs.export");
Route::get('das-logs', [DasLogController::class,'index']);
Route::get('das-logs/{stackId}', [DasLogController::class,'index']);

Route::get('measurements/datatable', [MeasurementController::class,'datatable'])->name("measurement.datatable");
Route::get('measurements/export', [MeasurementController::class,'export'])->name("measurement.export");
Route::post('measurements/export-simpel', [MeasurementController::class,'exportKLHK'])->name("measurement.exportKLHK");
Route::get('measurements', [MeasurementController::class,'index']);
Route::get('measurements/{stackId}', [MeasurementController::class,'index']);

Route::get('rca-logs/datatable', [RcaLogController::class,'datatable'])->name("rca-logs.datatable");
Route::get('rca-logs/export', [RcaLogController::class,'export'])->name("rca-logs.export");
Route::get('rca-logs', [RcaLogController::class,'index']);
Route::get('rca-logs/{stackId}', [RcaLogController::class,'index']);

Route::get('configuration',[ConfigurationController::class,'index'])->name("configuration.index");
Route::post('configuration',[ConfigurationController::class,'store'])->name("configuration.store");

Route::get("auth/login",[LoginController::class,'index'])->name("login");
Route::post("auth/login",[LoginController::class,'store'])->name("login.store");
Route::get("auth/logout",[LoginController::class,'logout'])->name("login.logout");


Route::group(["prefix" => "master","as" => "master.","middleware" => ["auth"]], function(){
    // Stack
    Route::get("stack/datatable", [StackController::class,"datatable"])->name("stack.datatable");
    Route::resource('stack', StackController::class)->except("edit");
    // Sensor
    Route::get("sensor/datatable", [SensorController::class,"datatable"])->name("sensor.datatable");
    Route::resource('sensor', SensorController::class)->except("edit");
    // Reference
    Route::get("reference/datatable", [ReferenceController::class,"datatable"])->name("reference.datatable");
    Route::resource('reference', ReferenceController::class)->except("edit");
    // Sensor
    Route::get("unit/datatable", [UnitController::class,"datatable"])->name("unit.datatable");
    Route::resource('unit', UnitController::class)->except("edit");
    // User
    Route::get("user/datatable", [UserController::class,"datatable"])->name("user.datatable");
    Route::resource('user', UserController::class)->except("edit");
});
