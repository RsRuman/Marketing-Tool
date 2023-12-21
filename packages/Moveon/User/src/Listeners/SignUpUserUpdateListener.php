<?php

namespace Moveon\User\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Moveon\User\Events\SignUpUserUpdateEvent;

class SignUpUserUpdateListener implements ShouldQueue
{
    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(SignUpUserUpdateEvent $event): void
    {
        $event->user->update([
            'origin_id' => $event->user->id,
            'token'     => bin2hex(random_bytes(32)) . $event->user->id
        ]);
    }
}
