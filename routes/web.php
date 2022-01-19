<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return redirect('login');
});

Auth::routes(['verify' => true]);
Route::middleware(['auth'])->group(function () {
  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  Route::any('pdf-to-csv', [App\Http\Controllers\PdfToCsvController::class, 'convertPdfToCsv'])->name('pdf-to-csv');

  Route::any('pdf-data', [App\Http\Controllers\PdfToCsvController::class, 'convertPdfData'])->name('pdf-data');

  Route::get('generate-excel',[App\Http\Controllers\PdfToCsvController::class, 'generateCsv'])->name('generate-csv');

  Route::get('generate-excel-gem',[App\Http\Controllers\PdfToCsvController::class, 'generateCsvGem'])->name('generate-excel-gem');

});