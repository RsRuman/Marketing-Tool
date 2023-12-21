<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Moveon\Segmentation\Models\FilterCriteria;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('filter_criterias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filter_id')->nullable();
            $table->boolean('is_parent')->nullable();
            $table->string('key');
            $table->string('label')->nullable();
            $table->string('value')->nullable();
            $table->string('value_type')->nullable();
            $table->string('status')->default(FilterCriteria::STATUS_ACTIVE);
            $table->timestamps();

            $table->foreign('filter_id')->references('id')->on('filters')->cascadeOnDelete();
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
