<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to add the column (only if it doesn't exist)
        DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS credits DECIMAL(10,2) DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the column if it exists
        DB::statement('ALTER TABLE users DROP COLUMN IF EXISTS credits');
    }
};
