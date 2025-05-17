<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvidenceSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $evidence;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evidence)
    {
        $this->evidence = $evidence;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Evidence Submission Confirmation')
                    ->view('emails.evidence-submitted');
    }
}