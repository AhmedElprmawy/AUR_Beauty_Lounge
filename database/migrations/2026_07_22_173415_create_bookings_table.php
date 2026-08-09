<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->foreignId('service_id')->constrained()->onDelete('cascade');
                $table->foreignId('staff_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->time('time');
                $table->text('notes')->nullable();
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
                $table->decimal('total_price', 10, 2)->default(0);
                $table->decimal('deposit', 10, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};