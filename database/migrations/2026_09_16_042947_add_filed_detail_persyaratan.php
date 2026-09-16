<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiledDetailPersyaratan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_persyaratan', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('berkas');
        });
    }

    public function down()
    {
        Schema::table('detail_persyaratan', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
