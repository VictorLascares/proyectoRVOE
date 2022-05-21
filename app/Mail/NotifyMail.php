<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class NotifyMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $meta,$career,$institution;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($meta,$career,$institution)
    {
        $this->meta = $meta;
        $this->career = $career;
        $this->institution = $institution;
    }



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $meta = $this->meta;
        $career = $this->career;
        $institution = $this->institution;
        return $this->view('emails.createdRequisition', compact('meta','career','institution'));
    }
}
