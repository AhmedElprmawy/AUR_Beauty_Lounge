<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditPasswordToBookingsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bookings')) {
            if (!Schema::hasColumn('bookings', 'edit_password')) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->string('edit_password')->nullable()->after('notes');
                });
            }

            if (!Schema::hasColumn('bookings', 'edit_password_expires_at')) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->timestamp('edit_password_expires_at')->nullable()->after('edit_password');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'edit_password')) {
                    $table->dropColumn('edit_password');
                }

                if (Schema::hasColumn('bookings', 'edit_password_expires_at')) {
                    $table->dropColumn('edit_password_expires_at');
                }
            });
        }
    }
}