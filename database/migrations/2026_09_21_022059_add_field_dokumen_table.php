<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('publikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_dokumen')->default(false)->after('judul');
        });
    }

    public function down()
    {
        Schema::table('publikasi', function (Blueprint $table) {
            $table->dropColumn('jenis_dokumen');
        });
    }
};