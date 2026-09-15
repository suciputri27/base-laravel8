<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfileDinasTable extends Migration
{
    public function up()
    {
        Schema::table('profile_dinas', function (Blueprint $table) {
            $table->renameColumn('profile', 'visi');
        });

        Schema::table('profile_dinas', function (Blueprint $table) {
            $table->longText('misi')->nullable()->after('visi');
            $table->longText('motto')->nullable()->after('misi');
            $table->longText('tupoksi')->nullable()->after('motto');
            $table->longText('sejarah')->nullable()->after('tupoksi');
        });
    }

    public function down()
    {
        Schema::table('profile_dinas', function (Blueprint $table) {
            $table->dropColumn(['misi', 'motto', 'tupoksi', 'sejarah']);
        });

        Schema::table('profile_dinas', function (Blueprint $table) {
            $table->renameColumn('visi', 'profile');
        });
    }
}
