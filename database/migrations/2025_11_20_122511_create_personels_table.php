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
            $table->bigIncrements('id');

            // İlişki Kurma (Laravel 6 ve sonrası için en sağlam yöntem)
            // 1. Önce ID sütunu aç (Negatif olamaz - unsigned)
            $table->unsignedBigInteger('departman_id');

            // 2. Bu ID'nin 'departmans' tablosundaki 'id'ye bağlı olduğunu söyle (Foreign Key)
            $table->foreign('departman_id')->references('id')->on('departmans')->onDelete('cascade');
            // onDelete('cascade'): Departman silinirse, ona bağlı personelleri de sil demektir.

            $table->string('ad_soyad');
            $table->string('email')->unique();
            // $table->string('departman'); <-- ESKİSİNİ SİLDİK
            $table->decimal('maas', 10, 2)->nullable();
            $table->date('ise_baslama_tarihi')->nullable();
            $table->string('gorsel')->nullable();
            $table->softDeletes(); // Soft delete eklemiştik, dursun
            $table->timestamps();
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
