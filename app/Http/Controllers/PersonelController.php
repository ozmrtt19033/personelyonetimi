<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personel;
use App\Models\Departman;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Exports\PersonelExport;
use Barryvdh\DomPDF\Facade\Pdf;

//mail işlemleri için;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

//excel çıktısı için;
use Maatwebsite\Excel\Facades\Excel;

//excel çıktısı için;

class PersonelController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // 1. Sorguyu Hazırla (Henüz çekme, bekle)
        $query = Personel::with(['departman', 'projects'])->latest();

        // 2. Arama var mı?
        if ($request->has('search')) {
            $search = $request->search;
            // İsimde VEYA Departman adında ara
            $query->where(function ($q) use ($search) {
                $q->where('ad_soyad', 'like', "%$search%")
                    ->orWhereHas('departman', function ($d) use ($search) {
                        $d->where('ad', 'like', "%$search%");
                    });
            });
        }

        // 3. Verileri Çek
        $personeller = $query->get();

        // 4. AJAX İsteği mi? (JavaScript mi soruyor?)
        if ($request->ajax()) {
            // Sadece tablo gövdesini (HTML) render edip gönder
            return view('personel.tbody', compact('personeller'))->render();
        }

        // 5. Normal İstek (Sayfayı komple gönder)
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
// Dosyanın en tepesine şunu eklemeyi unutma:
// use Illuminate\Support\Facades\Log;
// use App\Models\Personel;

    public function store(Request $request)
    {
        // 1. VALIDASYON (Zırhlı Kapı)
        $request->validate([
            'ad_soyad' => 'required|max:255',
            'email' => 'required|email|unique:personels',
            'departman_id' => 'required|exists:departmans,id',
            'maas' => 'nullable|numeric',
            'ise_baslama_tarihi' => 'nullable|date',
            // EKSİK OLAN GÖRSEL KURALINI GERİ EKLEDİK:
            'gorsel' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ad_soyad.required' => 'Reis, isim yazmayı unuttun!',
            'email.unique' => 'Bu mail adresiyle zaten kayıt var.',
            'departman_id.required' => 'Departman seçimi zorunludur!',
            'departman_id.exists' => 'Seçilen departman geçersiz!',
            'gorsel.image' => 'Lütfen geçerli bir resim dosyası seçin.',
            'gorsel.max' => 'Resim boyutu 2MB dan büyük olamaz.',
        ]);

        // 2. RİSKLİ İŞLEMLER (Try-Catch)
        try {
            $data = $request->all();

            // Dosya Yükleme İşlemi
            if ($request->hasFile('gorsel')) {
                $file = $request->file('gorsel');
                $filename = time() . '.' . $file->getClientOriginalExtension();

                // Public diskine kaydet
                $file->storeAs('uploads', $filename, 'public');

                // Veritabanı yolunu ayarla
                $data['gorsel'] = 'uploads/' . $filename;
            }

            // NOT: Buradaki "if empty departman_id" kontrolünü sildim.
            // Çünkü yukarıdaki validate() fonksiyonu bunu zaten garanti ediyor.

            // Veritabanına Kayıt
            $personel = Personel::create($data);

            // Kaydettiğimiz personelin email adresine gönderiyoruz
            Mail::to($personel->email)->send(new WelcomeMail($personel));

            // Başarılı Yönlendirme
            return redirect()->route('personel.index')
                ->with('success', 'Personel başarıyla kaydedildi reis!');

        } catch (\Exception $e) {
            // --- HATA YAKALAMA ---

            // 1. Log dosyasına teknik detayı yaz (Bizim için)
            Log::error('Personel eklenirken hata oluştu: ' . $e->getMessage());

            // 2. Kullanıcıya genel bir hata mesajı dön
            return redirect()->back()
                ->withInput() // Formu silme, yazdıkları kalsın
                ->with('error', 'Bir hata oluştu reis! Teknik ekip inceliyor. (Hata Kodu: P-500)');
        }
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

        try {
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
        } catch (\Exception $e) {
            Log::error('Güncelleme olurken hata oluştu reis: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Bir hata oluştu inceleniyor');

        }


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

    //excel dökümanı export işlemi
    public function export()
    {
        // 'personeller.xlsx' adıyla indir.
        return Excel::download(new PersonelExport, 'personel_listesi.xlsx');
    }

    // PDF İNDİRME METODU
    public function downloadPdf($id)
    {
        // 1. Personeli bul (Projeleri ve departmanıyla beraber)
        $personel = Personel::with(['departman', 'projects'])->findOrFail($id);

        $personel = Personel::with(['departman', 'projects'])->findOrFail($id);

        // 2. PDF Ayarları
        // 'personel.pdf' adında bir blade dosyası oluşturacağız.
        // loadView: Hangi tasarımı kullanayım?
        $pdf = Pdf::loadView('personel.pdf', compact('personel'));

        // 3. İndir (stream: tarayıcıda açar, download: direkt indirir)
        // Biz 'download' yapalım.
        return $pdf->download('personel-kimlik-' . $personel->id . '.pdf');
    }


}
