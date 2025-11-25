<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = []; // Hızlıca her şeyi doldurabilelim

    // Bu projenin çalışanlarını getir
    public function personels()
    {
        return $this->belongsToMany(Personel::class, 'personel_project');
    }
}
