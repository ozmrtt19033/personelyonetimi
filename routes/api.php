<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PersonelController;
use App\Http\Controllers\Api\AuthController;

// 1. HERKESE AÇIK ROTALAR (Public)
Route::post('/login', [AuthController::class, 'login']); // Giriş yapmak serbest

// 2. KORUMALI ROTALAR (Private - Sadece Anahtarı Olanlar)
// 'auth:sanctum' demek: "Anahtarını göstermeyen giremez" demektir.
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/personel', [PersonelController::class, 'index']); // Listeyi görmek bile yasak olsun
    Route::post('/personel', [PersonelController::class, 'store']); // Kayıt etmek yasak
    Route::post('/logout', [AuthController::class, 'logout']); // Çıkış yapmak

    Route::get('/profile', [AuthController::class, 'profile']); // Profilimi gör
    Route::post('/profile/password', [AuthController::class, 'updatePassword']); // Şifremi değiştir

});
