<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanLanjutModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_permohonan_lanjut';
    protected $primaryKey = 'permohonan_lanjut_id';
    protected $fillable = [
        'user_id', 
        'kategori', 
        'status_pemohon', 
        'judul_pemohon', 
        'deskripsi', 
        'dokumen_pendukung', 
        'status', 
        'jawaban',
        'alasan_penolakan', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    public function m_user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}
