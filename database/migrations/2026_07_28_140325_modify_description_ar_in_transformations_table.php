<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('transformations') && Schema::hasColumn('transformations', 'description_ar') && Schema::hasColumn('transformations', 'description_en')) {
            Schema::table('transformations', function (Blueprint $table) {
                // ✅ جعل العمود nullable (اختياري)
                $table->text('description_ar')->nullable()->change();
                $table->text('description_en')->nullable()->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('transformations') && Schema::hasColumn('transformations', 'description_ar') && Schema::hasColumn('transformations', 'description_en')) {
            Schema::table('transformations', function (Blueprint $table) {
                $table->text('description_ar')->nullable(false)->change();
                $table->text('description_en')->nullable(false)->change();
            });
        }
    }
};