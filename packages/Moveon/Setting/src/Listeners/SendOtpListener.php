<?php

namespace Moveon\Setting\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Moveon\Setting\Events\SendOtpEvent;
use Moveon\Setting\Mail\OtpMail;

class SendOtpListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(SendOtpEvent $event): void
    {
        Mail::to($event->user->email)->send(new OtpMail($event->otp));
    }
}
