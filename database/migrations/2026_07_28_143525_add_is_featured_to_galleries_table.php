<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                if (!Schema::hasColumn('galleries', 'is_featured')) {
                    $table->boolean('is_featured')->default(false)->after('caption');
                }

                if (!Schema::hasColumn('galleries', 'order')) {
                    $table->integer('order')->default(0)->after('is_featured');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                if (Schema::hasColumn('galleries', 'is_featured')) {
                    $table->dropColumn('is_featured');
                }

                if (Schema::hasColumn('galleries', 'order')) {
                    $table->dropColumn('order');
                }
            });
        }
    }
};