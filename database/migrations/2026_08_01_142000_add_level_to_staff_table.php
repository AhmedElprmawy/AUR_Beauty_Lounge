<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('staff') && !Schema::hasColumn('staff', 'level')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->string('level')->nullable()->default('Expert')->after('role_en');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('staff') && Schema::hasColumn('staff', 'level')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('level');
            });
        }
    }
};
