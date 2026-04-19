<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');
});

Route::get('/catalog', function () {
    return inertia('Catalog');
});

Route::get('/dashboard', function () {
    return inertia('Dashboard');
})->middleware('auth');
