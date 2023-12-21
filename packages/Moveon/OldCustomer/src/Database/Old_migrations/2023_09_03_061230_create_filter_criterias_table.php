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
        Schema::create('filter_criterias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filter_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('name');
            $table->string('value')->nullable();
            $table->string('value_type')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();

            $table->foreign('filter_id')->references('id')->on('filters');
            $table->foreign('parent_id')->references('id')->on('filter_criterias');
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filter_criterias');
    }
};
