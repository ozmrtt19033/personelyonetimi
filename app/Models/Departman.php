<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departman extends Model
{
    protected $table = 'departmans';
    protected $fillable = ['ad'];

    // İLİŞKİ: Bir departmanın ÇOK personeli olur (Has Many)
    public function personeller()
    {
        return $this->hasMany(Personel::class, 'departman_id', 'id');
    }
}
