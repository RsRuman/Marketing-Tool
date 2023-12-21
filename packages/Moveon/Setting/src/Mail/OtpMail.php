<?php

namespace Moveon\Setting\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class OtpMail extends Mailable
{
    public string $otp;

    public function __construct($otp)
    {
        $this->otp =  $otp;
    }

    public function build(): OtpMail
    {
        return $this->view('Moveon.Setting.otp_email_template')->with(['otp' => $this->otp]);
    }
}
