<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('custom_services')) {
            Schema::create('custom_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')
                    ->constrained('bookings')
                    ->cascadeOnDelete();
                $table->string('name');
                $table->decimal('price', 10, 2);
                $table->foreignId('staff_id')
                    ->nullable()
                    ->constrained('staff')
                    ->nullOnDelete();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('custom_services');
    }
};