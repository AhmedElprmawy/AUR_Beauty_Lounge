<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bridals')) {
            Schema::create('bridals', function (Blueprint $table) {
                $table->id();
                $table->string('title_ar');
                $table->string('title_en')->nullable();
                $table->text('description_ar');
                $table->text('description_en')->nullable();
                $table->string('image')->nullable();
                $table->string('small_image')->nullable();
                $table->string('stats_number')->default('1500+');
                $table->string('stats_label_ar')->default('عروس جميلة');
                $table->string('stats_label_en')->default('Beautiful Brides');
                $table->json('features_ar');
                $table->json('features_en')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bridals');
    }
};