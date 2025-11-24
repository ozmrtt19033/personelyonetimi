<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('departmans', function (Blueprint $table) { // Tablo adı 'departmans' olsun
            $table->bigIncrements('id');
            $table->string('ad'); // Departman adı (Örn: Yazılım)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departmen');
    }
};
