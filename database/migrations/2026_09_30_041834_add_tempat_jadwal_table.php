<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jadwal_layanan', function (Blueprint $table) {
            $table->text('tempat')->nullable();
        });
    }

    public function down()
    {
        Schema::table('jadwal_layanan', function (Blueprint $table) {
            $table->dropColumn('tempat');
        });
    }
};