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
        Schema::table('jenis_dokumen', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->comment('1 = peraturan, 2 = dokumen')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('jenis_dokumen', function (Blueprint $table) {
            $table->boolean('type')->default(true)->change();
        });
    }
};