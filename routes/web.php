<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', fn () => Inertia::render('Home'));
Route::get('/favorites', fn () => Inertia::render('Favorites'));
Route::get('/assets/{id}', fn (string $id) => Inertia::render('AssetShow', ['id' => $id]));