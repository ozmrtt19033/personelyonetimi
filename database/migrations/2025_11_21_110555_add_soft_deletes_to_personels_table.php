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
        Schema::table('personels', function (Blueprint $table) {
            // Bu komut otomatik olarak 'deleted_at' sütunu ekler
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('personels', function (Blueprint $table) {
            // Geri alırsak sütunu siler
            $table->dropSoftDeletes();
        });
    }
};
