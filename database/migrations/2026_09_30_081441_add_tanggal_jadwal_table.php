<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jadwal_layanan', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('day'); 
            $table->string('day')->nullable()->change(); 
        });
    }

    public function down()
    {
        Schema::table('jadwal_layanan', function (Blueprint $table) {
            $table->dropColumn('tanggal');
            $table->dropColumn('day');
        });
    }
};