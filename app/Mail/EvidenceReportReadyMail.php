<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Evidence;

class EvidenceReportReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $evidence;

    public function __construct(Evidence $evidence)
    {
        $this->evidence = $evidence;
    }

    public function build()
    {
        $mail = $this->subject('Evidence Report Ready - ' . $this->evidence->type)
                    ->view('emails.evidence-report-ready')
                    ->with([
                        'evidence' => $this->evidence,
                        'referenceNumber' => 'GFSL-' . date('Y', strtotime($this->evidence->created_at)) . '-' . $this->evidence->id,
                    ]);

        // Attach the report file if it exists
        if ($this->evidence->report_path && Storage::exists('public/' . $this->evidence->report_path)) {
            $mail->attach(
                Storage::path('public/' . $this->evidence->report_path),
                [
                    'as' => 'Evidence_Report_' . $this->evidence->id . '.' . pathinfo($this->evidence->report_path, PATHINFO_EXTENSION),
                    'mime' => Storage::mimeType('public/' . $this->evidence->report_path)
                ]
            );
        }

        return $mail;
    }
} 