<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Personel;
use App\Models\Departman;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

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
//        $data = $request->only([
//            'ad_soyad',
//            'email',
//            'departman_id',
//            'maas',
//            'ise_baslama_tarihi'
//        ]);
        $data = $request->all();

        // 2. DOSYA YÜKLEME İŞLEMİ
        if ($request->hasFile('gorsel')) {
            $file = $request->file('gorsel');

            // Benzersiz isim ver (Zaman damgası + uzantı) -> Örn: 1678944.jpg
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Dosyayı 'public/uploads' klasörüne kaydet
            $file->storeAs('uploads', $filename, 'public');

            // Veritabanına kaydedilecek yolu ayarla ("uploads/1678944.jpg")
            $data['gorsel'] = 'uploads/' . $filename;
        }


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

    //PersonelProjeleri sayfasının görüntülenebilmesi için gerekli olan kodlama
    public function show(Personel $personel)
    {
        return view('personel.show', compact('personel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personel $personel)
    {
        // Departmanları veritabanından çek ve forma gönder
        $departmanlar = Departman::all();
        $projects = Project::all();
        // Örn: [1, 3] -> Bu sayede Blade'de "bu seçili mi?" diye bakabileceğiz.
        $secili_projeler = $personel->projects->pluck('id')->toArray();

        return view('personel.edit', compact('personel', 'departmanlar', 'projects', 'secili_projeler'));
    }

    /**
     * Update the specified resource in storage.
     */
// DİKKAT: update(Request $request, Personel $personel) olmalı!
// Eğer update(Request $request, $id) veya update(Request $request, $personel) yazarsan HATA ALIRSIN.

    public function update(\Illuminate\Http\Request $request, \App\Models\Personel $personel)
    {
        // 1. VALIDASYON (Sadece temel bilgiler)
       $data = $request->validate([
            'ad_soyad' => 'required|max:255',
            'email' => 'required|email|unique:personels,email,' . $personel->id,
            'departman_id' => 'required|exists:departmans,id',
            'maas' => 'nullable|numeric',
            'ise_baslama_tarihi' => 'nullable|date',
            'gorsel' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'departman_id.required' => 'Departman seçimi zorunludur!',
        ]);

        // 2. TEMEL BİLGİLERİ GÜNCELLE
        // Formdan gelen yazılı verileri (ad, soyad, email vb.) güncelliyoruz.
        $personel->update($request->only([
            'ad_soyad',
            'email',
            'departman_id',
            'maas',
            'ise_baslama_tarihi'
        ]));
        if ($request->hasFile('gorsel')) {

            // A) Eski resim varsa ve dosya yerinde duruyorsa SİL (Temizlik)
            if ($personel->gorsel && \Illuminate\Support\Facades\Storage::exists('public/' . $personel->gorsel)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $personel->gorsel);
            }

            // B) Yeni resmi yükle
            $file = $request->file('gorsel');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');

            // C) Veritabanına yazılacak yeni yolu ayarla
            $data['gorsel'] = 'uploads/' . $filename;
        }

        $personel->update($data);


        // 3. PROJE ATAMA (SYNC - Many to Many)
        // Burası işin kalbi. Formdaki çoklu seçim kutusundan gelen 'projects' dizisi.
        if (isset($request->projects)) {
            // sync(): Listede olanları ekler, olmayanları siler. Tam eşitleme yapar.
            $personel->projects()->sync($request->projects);
        } else {
            // Eğer kutudaki tüm seçimleri kaldırdıysa (hiçbir şey seçmediyse),
            // o personelin tüm proje bağlantılarını kopar.
            $personel->projects()->detach();
        }

        // 4. YÖNLENDİRME
        return redirect()->route('personel.index')
            ->with('success', 'Personel ve proje görevleri güncellendi reis!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personel $personel)
    {
        $personel->delete();
        /*$personel->forceDelete();*/ //tamamen silmek için gerekli olan kodlama...
        return redirect()->route('personel.index')->with('success', 'Personel kaydı başarıyla silindi.');
    }
}
