<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonelController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/giris-admin', function () {
    auth()->loginUsingId(1); // ID 1: Armağan Bey (Admin)
    return redirect('/personel');
});

// Hızlıca Stajyer olarak giriş yap
Route::get('/giris-stajyer', function () {
    auth()->loginUsingId(2); // ID 2: Stajyer Ahmet (Personel)
    return redirect('/personel');
});


Route::get('/personel', [PersonelController::class, 'index'])->name('personel.index');
Route::get('/personel/{personel}', [PersonelController::class, 'show'])->name('personel.show');

// 2. SADECE ADMIN'İN GİREBİLECEĞİ ROTALAR (Create, Edit, Delete)
// middleware(['auth', 'admin']) -> Hem giriş yapmış olsun HEM DE admin olsun
Route::middleware(['auth', 'admin'])->group(function () {

    // Ekleme
    Route::get('/personel/create', [PersonelController::class, 'create'])->name('personel.create');
    Route::post('/personel', [PersonelController::class, 'store'])->name('personel.store');

    // Düzenleme
    Route::get('/personel/{personel}/edit', [PersonelController::class, 'edit'])->name('personel.edit');
    Route::put('/personel/{personel}', [PersonelController::class, 'update'])->name('personel.update');

    // Silme
    Route::delete('/personel/{personel}', [PersonelController::class, 'destroy'])->name('personel.destroy');
});


////Route::resource('personel', PersonelController::class);
//
//// 1. Listeleme Sayfası (GET) -> EKSİK OLAN BUYDU!
//Route::get('/personel', [PersonelController::class, 'index'])->name('personel.index');
//
//// 2. Form Gösterme Sayfası (GET)
//Route::get('/personel/create', [PersonelController::class, 'create'])->name('personel.create');
//
//// 3. Kaydetme İşlemi (POST)
//Route::post('/personel', [PersonelController::class, 'store'])->name('personel.store');
//
//// güncelleme route
//Route::put('/personel/{personel}', [PersonelController::class, 'update'])->name('personel.update');
//Route::get('/personel/{personel}/edit', [PersonelController::class, 'edit'])->name('personel.edit');
//
//// silme işlemi route yapısı
//Route::delete('/personel/{personel}', [PersonelController::class, 'destroy'])->name('personel.destroy');
//Route::view('/api-test', 'api_test');
//
//Route::get('/personel/{personel}', [\App\Http\Controllers\PersonelController::class, 'show'])->name('personel.show');


