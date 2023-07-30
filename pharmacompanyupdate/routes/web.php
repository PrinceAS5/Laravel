<?php

use App\Http\Controllers\UpdateController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::get('/', [AuthController::class, 'index'])->name('home');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register-page', [AuthController::class, 'registerPage'])->name('registerPage');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/update-page', [UpdateController::class, 'index']);
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/store', [UpdateController::class, 'update'])->name('store');
    Route::post('edit-update/{id}', [UpdateController::class, 'edit'])->name('edit');
    Route::post('editUpdate/{id}', [UpdateController::class, 'editUpdate'])->name('update');
    Route::delete('delete/{id}', [UpdateController::class, 'delete'])->name('delete');
});
