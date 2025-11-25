<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'personels';

    // departman_id'yi fillable'a eklemeyi UNUTMA!
    protected $fillable = [
        'departman_id', // <-- Yeni eklediğimiz
        'ad_soyad',
        'email',
        'maas',
        'ise_baslama_tarihi',
        'gorsel',
    ];

    // İLİŞKİ: Bir personel, TEK BİR departmana aittir (Belongs To)
    public function departman()
    {
        return $this->belongsTo(Departman::class, 'departman_id', 'id');
    }

    public function projects()
    {
        // belongsTo DEĞİL, belongsToMany (Çoğul)
        return $this->belongsToMany(Project::class, 'personel_project');
    }
}
