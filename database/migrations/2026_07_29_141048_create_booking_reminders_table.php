<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('booking_reminders')) {
            Schema::create('booking_reminders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
                $table->string('type'); // seven_days, one_day_customer, one_day_owner
                $table->string('recipient_type'); // customer, owner
                $table->string('recipient_phone');
                $table->datetime('scheduled_at');
                $table->datetime('sent_at')->nullable();
                $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
                $table->text('error_message')->nullable();
                $table->text('message')->nullable();
                $table->timestamps();

                // ✅ منع التكرار
                $table->unique(['booking_id', 'type', 'recipient_type']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('booking_reminders');
    }
};