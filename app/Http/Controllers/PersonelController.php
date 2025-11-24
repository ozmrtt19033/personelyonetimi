<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personel;
use App\Models\Departman;

class PersonelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Tüm personelleri veritabanından çek
        // ATC Yazılım notu: Eskiden Personel::all() çok kullanılırdı.
        // Ama biz modern ve performanslı olsun diye 'latest' (en son eklenen en üstte) kullanalım.

        // Departman ilişkisini de yükle (N+1 query problemini önlemek için)
        $personeller = Personel::with('departman')->latest()->get();

        // Not: Eğer en başta 'use App\Models\Personel;' eklediysen başına \App\Models\ yazmana gerek yok.

        // 2. Veriyi 'personel.index' view dosyasına paketleyip gönder
        return view('personel.index', compact('personeller'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Departmanları veritabanından çek ve forma gönder
        $departmanlar = Departman::all();
        // resources/views/personel/create.blade.php dosyasını kullanıcıya gösterir
        return view('personel.create', compact('departmanlar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDASYON (Doğrulama)
        // Formdan gelen veriler kurallara uyuyor mu?
        // Uymazsa Laravel otomatik olarak hata mesajlarıyla birlikte formu geri yükler.
        $request->validate([
            'ad_soyad' => 'required|max:255', // Boş olamaz, max 255 karakter
            'email' => 'required|email|unique:personels', // Email formatı olmalı ve DB'de aynısı olmamalı
            'departman_id' => 'required|exists:departmans,id', // Seçilmesi zorunlu ve departmans tablosunda olmalı
            'maas' => 'nullable|numeric', // Boş olabilir ama doluysa sayı olmalı
            'ise_baslama_tarihi' => 'nullable|date',
        ], [
            // Özel Hata Mesajları (Opsiyonel - Mülakatta +Puan getirir)
            'ad_soyad.required' => 'Reis, isim yazmayı unuttun!',
            'email.unique' => 'Bu mail adresiyle zaten kayıt var.',
            'departman_id.required' => 'Departman seçimi zorunludur!',
            'departman_id.exists' => 'Seçilen departman geçersiz!',
        ]);

        // 2. KAYIT İŞLEMİ (Mass Assignment)
        // Model dosyasında $fillable alanlarını tanımladığımız için
        // tek satırda tüm veriyi basabiliriz.
        // ATC Notu: Laravel 6'da Model namespace'i App\Personel olabilir, dikkat et.
        // Sadece gerekli alanları al (departman_id'nin geldiğinden emin ol)
        $data = $request->only([
            'ad_soyad',
            'email',
            'departman_id',
            'maas',
            'ise_baslama_tarihi'
        ]);
        
        // departman_id'nin boş olmadığından emin ol
        if (empty($data['departman_id'])) {
            return back()->withErrors(['departman_id' => 'Departman seçimi zorunludur!'])->withInput();
        }
        
        Personel::create($data);

        /* Eğer $fillable kullanmasaydık veya Laravel 6'da eski usül isteselerdi
           şöyle yazardık (Uzun Yol):

           $personel = new \App\Models\Personel();
           $personel->ad_soyad = $request->ad_soyad;
           $personel->email = $request->email;
           ...
           $personel->save();
        */

        // 3. YÖNLENDİRME
        // İşlem bitince listeye geri dön ve "Başarılı" mesajı taşı.
        return redirect()->route('personel.index')
            ->with('success', 'Personel başarıyla kaydedildi reis!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personel $personel)
    {
        // Departmanları veritabanından çek ve forma gönder
        $departmanlar = Departman::all();
        return view('personel.edit', compact('personel', 'departmanlar'));
    }

    /**
     * Update the specified resource in storage.
     */
// DİKKAT: update(Request $request, Personel $personel) olmalı!
// Eğer update(Request $request, $id) veya update(Request $request, $personel) yazarsan HATA ALIRSIN.

    public function update(\Illuminate\Http\Request $request, \App\Models\Personel $personel)
    {
        // 1. Validasyon
        $request->validate([
            'ad_soyad' => 'required|max:255',
            // E-posta kontrolünde ignore kısmında $personel->id kullanıyoruz
            'email'    => 'required|email|unique:personels,email,'.$personel->id,
            'departman_id' => 'required|exists:departmans,id',
            'maas'     => 'nullable|numeric',
            'ise_baslama_tarihi' => 'nullable|date',
        ], [
            'departman_id.required' => 'Departman seçimi zorunludur!',
            'departman_id.exists' => 'Seçilen departman geçersiz!',
        ]);

        // 2. Güncelleme (Artık $personel bir nesne olduğu için update çalışır)
        // Sadece gerekli alanları al (departman_id'nin geldiğinden emin ol)
        $personel->update($request->only([
            'ad_soyad',
            'email',
            'departman_id',
            'maas',
            'ise_baslama_tarihi'
        ]));

        // 3. Yönlendirme
        return redirect()->route('personel.index')
            ->with('success', 'Personel güncellendi reis!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personel $personel)
    {
       $personel->delete();
       /*$personel->forceDelete();*/ //tamamen silmek için gerekli olan kodlama...
       return redirect()->route('personel.index')->with('success','Personel kaydı başarıyla silindi.');
    }
}
