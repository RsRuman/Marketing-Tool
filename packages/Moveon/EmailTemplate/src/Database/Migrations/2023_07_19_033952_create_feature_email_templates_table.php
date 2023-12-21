<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('design');
            $table->text('html');
            $table->string('status')->default('activate');
            $table->softDeletes();
            $table->timestamps();
        });

        $this->seed();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_email_templates');
    }

    public function seed(): void
    {
        Artisan::call('feature:template-seed');
    }
};
