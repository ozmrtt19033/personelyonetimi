<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonelController;

Route::get('/', function () {
    return view('welcome');
});


//Route::resource('personel', PersonelController::class);

// 1. Listeleme Sayfası (GET) -> EKSİK OLAN BUYDU!
Route::get('/personel', [PersonelController::class, 'index'])->name('personel.index');

// 2. Form Gösterme Sayfası (GET)
Route::get('/personel/create', [PersonelController::class, 'create'])->name('personel.create');

// 3. Kaydetme İşlemi (POST)
Route::post('/personel', [PersonelController::class, 'store'])->name('personel.store');

// güncelleme route
Route::put('/personel/{personel}', [PersonelController::class, 'update'])->name('personel.update');
Route::get('/personel/{personel}/edit', [PersonelController::class, 'edit'])->name('personel.edit');

// silme işlemi route yapısı
Route::delete('/personel/{personel}', [PersonelController::class, 'destroy'])->name('personel.destroy');
