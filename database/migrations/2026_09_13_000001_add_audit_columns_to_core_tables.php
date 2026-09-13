<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditColumnsToCoreTables extends Migration
{
    public function up()
    {
        foreach (['users', 'menus', 'categories', 'posts', 'profile_dinas'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('created_by')->nullable();
                $blueprint->unsignedBigInteger('updated_by')->nullable();
                $blueprint->unsignedBigInteger('deleted_by')->nullable();
            });
        }
    }

    public function down()
    {
        foreach (['users', 'menus', 'categories', 'posts', 'profile_dinas'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn(['created_by', 'updated_by', 'deleted_by']);
            });
        }
    }
}
