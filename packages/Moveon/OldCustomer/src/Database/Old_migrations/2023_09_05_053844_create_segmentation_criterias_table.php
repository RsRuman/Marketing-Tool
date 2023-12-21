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
        Schema::create('segmentation_criterias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('segmentation_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('name');
            $table->json('value')->nullable();
            $table->string('value_type')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();

            $table->foreign('segmentation_id')->references('id')->on('segmentations');
            $table->foreign('parent_id')->references('id')->on('segmentation_criterias');
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segmentation_criterias');
    }
};
