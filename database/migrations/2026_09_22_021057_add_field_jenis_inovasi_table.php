<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inovasi', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis')->default(false)->after('judul');
        });
    }

    public function down()
    {
        Schema::table('inovasi', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
