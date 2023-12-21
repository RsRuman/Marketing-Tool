<?php

namespace Moveon\Customer\OldCommands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Moveon\Customer\Database\Seeders\GroupSeeder;

class GroupSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeding group';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Artisan::call('db:seed', [
            '--class' => GroupSeeder::class
        ]);
    }
}
