<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToCoreTables extends Migration
{
    public function up()
    {
        foreach (['users', 'menus', 'categories', 'posts'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->softDeletes();
            });
        }
    }

    public function down()
    {
        foreach (['users', 'menus', 'categories', 'posts'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('deleted_at');
            });
        }
    }
}
