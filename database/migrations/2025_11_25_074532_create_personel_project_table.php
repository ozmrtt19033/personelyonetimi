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
        Schema::create('personel_project', function (Blueprint $table) {
            $table->bigIncrements('id');

            // 1. Personel ID
            $table->unsignedBigInteger('personel_id');
            $table->foreign('personel_id')->references('id')->on('personels')->onDelete('cascade');

            // 2. Proje ID
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            // Aynı personel aynı projeye 2 kere eklenmesin diye önlem alalım (Opsiyonel)
            // $table->unique(['personel_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personel_project');
    }
};
