<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('services') && !Schema::hasColumn('services', 'image_path')) {
            Schema::table('services', function (Blueprint $table) {
                // تغيير عمود image من string إلى nullable (إذا كان موجود)
                // وإضافة عمود image_path لتخزين مسار الصورة
                if (Schema::hasColumn('services', 'image')) {
                    $table->string('image')->nullable()->change();
                } else {
                    $table->string('image')->nullable();
                }
                $table->string('image_path')->nullable()->after('image');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'image_path')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }
};