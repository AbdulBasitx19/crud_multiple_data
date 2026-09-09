<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/people', [PersonController::class, 'index'])->name('people.index');
Route::get('/people/create', [PersonController::class, 'cerate'])->name('people.create');
Route::post('/people', [PersonController::class , 'store'])->name('people.store');
Route::get('/people/{person}/edit', [PersonController::class, 'edit'])->name('people.edit');
Route::put('people/{person}', [PersonController::class , 'update'])->name('people.update');
Route::delete('people/{person}', [PersonController::class, 'destroy'])->name('people.destory');
