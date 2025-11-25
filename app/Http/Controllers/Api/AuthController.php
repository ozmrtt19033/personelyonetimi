<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        // 1. Giriş Bilgilerini Kontrol Et
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Laravel'in Auth kontrolü
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // 3. KULLANICIYA TOKEN OLUŞTUR (Anahtar Teslimi)
            // 'ATC-Token' ismini biz verdik, istediğini verebilirsin.
            $token = $user->createToken('ATC-Token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Giriş başarılı reis, al anahtarın!',
                'token' => $token, // <-- İşte bu çok önemli
                'user' => $user
            ]);
        }

        // Giriş Başarısızsa
        return response()->json([
            'status' => false,
            'message' => 'Email veya şifre hatalı.'
        ], 401);
    }

    public function logout(Request $request)
    {
        // Çıkış yapınca tokeni sil (Anahtarı çöpe at)
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'message' => 'Çıkış yapıldı'
            ]
        );
    }

    //profil bilgilerini getir:::
    public function profile(Request $request)
    {
        // Token ile gelen kullanıcıyı yakala
        $user = $request->user();

        return response()->json([
            'status' => true,
            'message' => 'Profil bilgileri getirildi.',
            'data' => [
                'id' => $user->id,
                'ad_soyad' => $user->name,
                'email' => $user->email,
                'rutbe' => $user->role,
                'kayit_tarihi' => $user->created_at->format('d.m.Y'),
            ]
        ]);
    }

    //şifre değiştirme fonksiyonu
    public function updatePassword(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'eski_sifre' => 'required',
            'yeni_sifre' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Eksik veya hatalı bilgi.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->eski_sifre, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Eski şifreniz hatalı reis, değiştiremezsin!'
            ], 400);
        }

        $user->password = Hash::make($request->yeni_sifre);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Şifreniz başarıyla güncellendi.'
        ]);
    }


}
