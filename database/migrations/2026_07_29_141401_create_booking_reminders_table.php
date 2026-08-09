<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration was previously a duplicate of the full
     * booking_reminders migration. Converted to a safe no-op
     * to avoid duplicate table creation while preserving file
     * ordering/history.
     */
    public function up(): void
    {
        // Intentionally left empty to avoid creating the table twice.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op.
    }
};
