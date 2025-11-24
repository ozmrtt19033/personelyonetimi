<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


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

}
