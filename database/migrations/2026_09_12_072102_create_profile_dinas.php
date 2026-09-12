<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileDinas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_dinas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_website');
            $table->longText('tentang');
            $table->text('profile')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat');
            $table->string('no_telepon')->nullable();
            $table->string('no_whatsapp')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_dinas');
    }
}
