<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToPersonelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Eğer personels tablosunda deleted_at kolonu yoksa ekle
        if (! Schema::hasColumn('personels', 'deleted_at')) {
            Schema::table('personels', function (Blueprint $table) {
                $table->softDeletes(); // created_at gibi nullable timestamp: deleted_at
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eğer personels tablosunda deleted_at kolonu varsa sil
        if (Schema::hasColumn('personels', 'deleted_at')) {
            Schema::table('personels', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }
}
