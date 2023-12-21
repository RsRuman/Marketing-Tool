<?php

namespace Moveon\Image\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Moveon\Image\Database\Seeders\CategorySeeder;

class CategorySeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:category-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeding image categories';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Artisan::call('db:seed', [
            '--class' => CategorySeeder::class
        ]);
    }
}
