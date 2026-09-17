<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('persyaratan', function (Blueprint $table) {
            $table->boolean('cekdokumen')->default(false)->after('nama_persyaratan');
        });
    }

    public function down()
    {
        Schema::table('persyaratan', function (Blueprint $table) {
            $table->dropColumn('cekdokumen');
        });
    }
};