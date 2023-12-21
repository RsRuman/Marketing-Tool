<?php

namespace Moveon\User\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Moveon\Segmentation\Database\Seeders\FilterSeeder;
use Moveon\User\Events\FilterSeedEvent;

class FilterSeedListener implements ShouldQueue
{
    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(FilterSeedEvent $event): void
    {
        $filterSeeder = new FilterSeeder($event->user);
        $filterSeeder->run();
    }
}
