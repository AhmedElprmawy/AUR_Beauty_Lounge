<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('transformations')) {
            Schema::create('transformations', function (Blueprint $table) {
                $table->id();
                $table->string('category'); // hair, makeup
                $table->string('title_ar');
                $table->string('title_en')->nullable();
                $table->text('description_ar');
                $table->text('description_en')->nullable();
                $table->string('before_image');
                $table->string('after_image');
                $table->boolean('is_featured')->default(false);
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('transformations');
    }
};