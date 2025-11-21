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
        Schema::create('personels', function (Blueprint $table) {
            $table->id(); // Laravel 6'da bu: $table->bigIncrements('id');
            $table->string('ad_soyad');
            $table->string('email')->unique(); // Aynı mailden 2 tane olamaz
            $table->string('departman')->nullable(); // Boş bırakılabilir
            $table->decimal('maas', 10, 2)->nullable(); // Boş kalabilir
            $table->date('ise_baslama_tarihi')->nullable(); // Boş kalabilir
            $table->timestamps(); // created_at ve updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personels');
    }
};
