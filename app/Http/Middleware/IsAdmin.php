<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kullanıcı giriş yapmış mı? VE Rütbesi 'admin' mi?
        if (auth()->check() && auth()->user()->role == 'admin') {
            // Sorun yok, geçebilir
            return $next($request);
        }

        // 2. Değilse: DUR! (403 Hatası fırlat)
        abort(403, 'Bu işlemi yapmaya yetkiniz yok reis!');    }
}

