<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PermohonanNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $status;
    public $reason;
    public $kategori;
    public $status_pemohon;

    /**
     * Create a new message instance.
     */
    public function __construct($nama, $status, $kategori, $status_pemohon, $reason = null)
    {
        $this->nama = $nama;
        $this->status = $status;
        $this->reason = $reason;
        $this->kategori = $kategori;
        $this->status_pemohon = $status_pemohon;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Status Permohonan Anda')
                    ->view('emails.permohonan-notification');
    }
}
