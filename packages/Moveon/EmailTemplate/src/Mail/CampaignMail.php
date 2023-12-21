<?php

namespace Moveon\EmailTemplate\Mail;

use Illuminate\Mail\Mailable;

class CampaignMail extends Mailable
{

    public $htmlData;


    public function __construct($htmlData)
    {
        $this->htmlData = $htmlData;
    }

    public function build()
    {
        return $this->view('email.custom_template')->with(['htmlData' => $this->htmlData]);
    }
}
