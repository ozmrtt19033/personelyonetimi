<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonelController;

Route::get('/', function () {
    return view('welcome');
});

// --- TEST GİRİŞ ROTALARI ---
Route::get('/giris-admin', function () {
    auth()->loginUsingId(1);
    return redirect('/personel');
});

Route::get('/giris-stajyer', function () {
    auth()->loginUsingId(2);
    return redirect('/personel');
});

// 1. HERKESİN GÖREBİLECEĞİ GENEL ROTA (Listeleme)
Route::get('/personel', [PersonelController::class, 'index'])->name('personel.index');

// -----------------------------------------------------------
// 🚨 KRİTİK DEĞİŞİKLİK BURADA REİS 🚨
// Create, Edit gibi özel rotaları, {personel} rotasından ÖNCE yazmalıyız.
// O yüzden Middleware grubunu yukarı taşıdık.
// -----------------------------------------------------------

// 2. SADECE ADMIN'İN GİREBİLECEĞİ ROTALAR (Create, Edit, Delete)
Route::middleware(['auth', 'admin'])->group(function () {

    // Ekleme (Create rotası artık Show'dan önce olduğu için çalışacak!)
    Route::get('/personel/create', [PersonelController::class, 'create'])->name('personel.create');
    Route::post('/personel', [PersonelController::class, 'store'])->name('personel.store');

    // Düzenleme
    Route::get('/personel/{personel}/edit', [PersonelController::class, 'edit'])->name('personel.edit');
    Route::put('/personel/{personel}', [PersonelController::class, 'update'])->name('personel.update');

    // Silme
    Route::delete('/personel/{personel}', [PersonelController::class, 'destroy'])->name('personel.destroy');
});

// 3. DETAY GÖSTERME (SHOW) - EN SONA KOYDUK!
// Laravel yukarıdakilerden hiçbirini bulamazsa (create, edit değilse) buna bakacak.
Route::get('/personel/{personel}', [PersonelController::class, 'show'])->name('personel.show');
