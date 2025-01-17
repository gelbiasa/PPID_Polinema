<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HasilPertanyaanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $status;
    public $reason;
    public $kategori;
    public $status_pemohon;
    public $detailPertanyaan; // Tambahkan untuk menyimpan pertanyaan dan jawaban

    /**
     * Create a new message instance.
     */
    public function __construct($nama, $status, $kategori, $status_pemohon, $reason = null, $detailPertanyaan = [])
    {
        $this->nama = $nama;
        $this->status = $status;
        $this->reason = $reason;
        $this->kategori = $kategori;
        $this->status_pemohon = $status_pemohon;
        $this->detailPertanyaan = $detailPertanyaan; // Inisialisasi detail pertanyaan
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Status Permohonan Anda')
                    ->view('emails.hasil-pertanyaan');
    }
}
