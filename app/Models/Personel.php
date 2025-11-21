<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personel extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Tablo ismini elle belirtmek (Opsiyonel ama garanti olur)
    protected $table = 'personels';

    // Hangi sütunlara veri eklenmesine izin veriyorsun?
    // (Bunu yazmazsan formdan veri kaydederken hata alırsın!)
    protected $fillable = [
        'ad_soyad',
        'email',
        'departman',
        'ise_baslama_tarihi',
        'maas'
    ];

    // Alternatif Yöntem (ATC'de bunu da görebilirsin):
    // protected $guarded = []; // "Hiçbir şeyi koruma, her şeyi kaydet" demektir.
}
