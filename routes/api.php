<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\FavoriteController;

Route::get('/', fn () => response()->json(['hello world' => true]));
Route::get('/ping', fn () => response()->json(['pong' => true]));

Route::get('/assets', [AssetController::class, 'index']); 
Route::get('/assets/{id}', [AssetController::class, 'show']); 

Route::get('/favorites', [FavoriteController::class, 'index']);
Route::post('/favorites', [FavoriteController::class, 'store']); // body: { assetId }
Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);