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
        Schema::table('roles', function (Blueprint $table) {
            // Remove the unique constraint from the column
            $table->dropUnique(['name', 'guard_name']);

            // Add new column
            $table->unsignedBigInteger('user_id');

            // Add new unique constraint
            $table->unique(['name', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['name', 'user_id']);
            $table->dropColumn('user_id');
            $table->unique(['name', 'guard_name']);
        });
    }
};
