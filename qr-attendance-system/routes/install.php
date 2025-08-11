<?php

use App\Http\Controllers\Install\InstallController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'install', 'middleware' => 'web'], function () {
    Route::get('/', [InstallController::class, 'index'])->name('install.index');
    Route::post('/', [InstallController::class, 'index'])->name('install.process');
    Route::get('/requirements', [InstallController::class, 'checkRequirements'])->name('install.requirements');
    Route::get('/delete', [InstallController::class, 'deleteInstallDir'])->name('install.delete');
});