<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personel;

class PersonelController extends Controller
{
    public function index()
    {
        $personeller = Personel::all();

        return response()->json([
            'status' => true,
            'data' => $personeller,
            'message' => 'personeller başarıyla çekildi.'
        ], 200);
    }

    public function store(Request $request)
    {
        // 1. Validasyon Nesnesi Oluştur (DİKKAT: $request->validate DEĞİL)
        // Validator sınıfının tam yolunu (Facade) kullanıyoruz.
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'ad_soyad' => 'required|max:255',
            'email'    => 'required|email|unique:personels',
            'departman'=> 'required',
            'maas'     => 'nullable|numeric'
        ]);
        // 2. Hata Kontrolü (fails metodu sadece Validator nesnesinde çalışır)
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasyon hatası oluştu reis.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 3. Kayıt İşlemi
        $personel = Personel::create($request->all());

        // 4. Başarılı Cevabı
        return response()->json([
            'status' => true,
            'message' => 'Personel başarıyla kaydedildi.',
            'data' => $personel
        ], 201);
    }


}
