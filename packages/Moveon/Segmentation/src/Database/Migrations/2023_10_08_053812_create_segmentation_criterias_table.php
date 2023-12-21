<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Moveon\Segmentation\Models\SegmentationCriteria;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('segmentation_criterias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('segmentation_id')->nullable();
            $table->boolean('is_parent')->nullable();
            $table->string('key');
            $table->string('label')->nullable();
            $table->string('value')->nullable();
            $table->string('value_type')->nullable();
            $table->string('status')->default(SegmentationCriteria::STATUS_ACTIVE);
            $table->timestamps();

            $table->foreign('segmentation_id')->references('id')->on('segmentations')->cascadeOnDelete();
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
