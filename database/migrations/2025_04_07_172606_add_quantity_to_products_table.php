<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Quantity is already added in the create_products_table migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need for this migration
    }
};
