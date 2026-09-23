<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Bersihkan dulu data yang inovasi_id-nya tidak valid / null,
        // supaya constraint tidak gagal dibuat
        DB::table('berkas_inovasi')
            ->whereNull('inovasi_id')
            ->orWhereNotIn('inovasi_id', DB::table('inovasi')->pluck('id'))
            ->delete();

        Schema::table('berkas_inovasi', function (Blueprint $table) {
            $table->unsignedBigInteger('inovasi_id')->nullable(false)->change();

            $table->foreign('inovasi_id')
                ->references('id')->on('inovasi')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('berkas_inovasi', function (Blueprint $table) {
            $table->dropForeign(['inovasi_id']);
        });
    }
};