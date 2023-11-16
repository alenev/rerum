<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/documents', [DocumentsController::class, 'index'])->name('documents.index');
Route::get('/documents/search', [DocumentsController::class, 'searchForm'])->name('documents.searchForm');
Route::post('/documents/upload', [DocumentsController::class, 'upload'])->name('documents.upload');
Route::post('/documents/build-index/{filename}', [DocumentsController::class, 'buildIndex'])->name('documents.buildIndex');
Route::get('/documents/results', [DocumentsController::class, 'search'])->name('documents.search');
Route::post('/documents/rebuildIndex', [DocumentsController::class, 'rebuildIndex'])->name('documents.rebuildIndex');
