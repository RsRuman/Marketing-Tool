<?php

namespace Moveon\Setting\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendOtpEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $otp;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $otp)
    {
        $this->user = $user;
        $this->otp  = $otp;
    }
}
