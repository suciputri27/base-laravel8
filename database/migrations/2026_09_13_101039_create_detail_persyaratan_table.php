<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPersyaratanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_persyaratan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelayanan_id')->nullable();
            $table->unsignedBigInteger('persyaratan_id')->nullable();
            $table->string('berkas')->nullable();
            $table->timestamps();

            $table->index('pelayanan_id');
            $table->foreign('pelayanan_id')->references('id')->on('pelayanan')->onDelete('set null');
            $table->index('persyaratan_id');
            $table->foreign('persyaratan_id')->references('id')->on('persyaratan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_persyaratan');
    }
}
