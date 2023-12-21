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
        Schema::create('segmentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_segmentation_id');
            $table->string('name');
            $table->string('label');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('user_segmentation_id')->references('id')->on('user_segmentations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segmentations');
    }
};
