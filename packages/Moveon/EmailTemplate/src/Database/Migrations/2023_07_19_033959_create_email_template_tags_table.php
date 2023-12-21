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
        Schema::create('email_template_tags', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->json('value');
            $table->string('status')->default('activate');
            $table->timestamps();
        });

        $this->seed();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_template_tags');
    }

    /**
     * Seeding tags on migration
     * @return void
     */
    public function seed(): void
    {
        $tags = [
            'first_name' => [
                'name'  => 'First Name',
                'value' => '{{first_name}}'
            ],

            'last_name' => [
                'name'  => 'Last Name',
                'value' => '{{last_name}}'
            ],

            'company_name' => [
                'name'  => 'Company Name',
                'value' => '{{company_name}}'
            ]
        ];

        foreach ($tags as $key => $value) {
            # Find existing data
            $exist = \Moveon\EmailTemplate\Models\EmailTemplateTag::query()
                ->where('title', $key)->first();

            # If it does not exist will proceed
            if (!$exist) {
                \Moveon\EmailTemplate\Models\EmailTemplateTag::create([
                    'title' => $key,
                    'value' => $value
                ]);
            }
        }
    }
};
