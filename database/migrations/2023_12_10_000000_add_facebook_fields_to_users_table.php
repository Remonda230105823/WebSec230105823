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
        Schema::table('users', function (Blueprint $table) {
            // Add Facebook ID field - unique identifier for Facebook users
            if (!Schema::hasColumn('users', 'facebook_id')) {
                $table->string('facebook_id')->nullable()->unique()->after('remember_token');
            }
            
            // Add Facebook token field - for API access
            if (!Schema::hasColumn('users', 'facebook_token')) {
                $table->text('facebook_token')->nullable()->after('facebook_id');
            }
            
            // Add login_method field to track how the user authenticated
            if (!Schema::hasColumn('users', 'login_method')) {
                $table->string('login_method')->nullable()->after('facebook_token');
            }
            
            // Add profile picture URL field
            if (!Schema::hasColumn('users', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('login_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('facebook_id');
            $table->dropColumn('facebook_token');
            $table->dropColumn('login_method');
            $table->dropColumn('profile_picture');
        });
    }
}; 