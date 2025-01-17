<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HasilPermohonanMail extends Mailable
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
        $email = $this->subject('Status Permohonan Anda')
            ->view('emails.hasil-permohonan');

        // Tambahkan lampiran jika status adalah 'Disetujui' dan terdapat file jawaban
        if ($this->status === 'Disetujui' && $this->reason) {
            $email->attach(storage_path('app/public/' . $this->reason), [
                'as' => 'Dokumen_Jawaban.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}
