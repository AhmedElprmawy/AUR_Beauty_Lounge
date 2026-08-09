<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('bookings', 'total_price')) {
                    $table->decimal('total_price', 10, 2)->default(0)->after('notes');
                }

                if (!Schema::hasColumn('bookings', 'deposit')) {
                    $table->decimal('deposit', 10, 2)->default(0)->after('total_price');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'total_price')) {
                    $table->dropColumn('total_price');
                }

                if (Schema::hasColumn('bookings', 'deposit')) {
                    $table->dropColumn('deposit');
                }
            });
        }
    }
};