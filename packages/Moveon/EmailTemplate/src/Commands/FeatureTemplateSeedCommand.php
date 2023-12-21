<?php

namespace Moveon\EmailTemplate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Moveon\EmailTemplate\Database\Seeders\FeatureTemplateSeeder;

class FeatureTemplateSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:template-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeding feature template';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Artisan::call('db:seed', [
            '--class' => FeatureTemplateSeeder::class
        ]);
    }
}
