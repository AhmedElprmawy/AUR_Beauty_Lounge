<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'edit_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('edit_password')->nullable()->after('password');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'edit_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('edit_password');
            });
        }
    }
};