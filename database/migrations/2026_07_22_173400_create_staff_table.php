<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->id();
                $table->string('name_ar');
                $table->string('name_en');
                $table->string('role_ar');
                $table->string('role_en');
                $table->text('bio_ar')->nullable();
                $table->text('bio_en')->nullable();
                $table->string('image')->nullable();
                $table->string('instagram')->nullable();
                $table->string('twitter')->nullable();
                $table->integer('experience_years')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('staff');
    }
};