<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotifikasiAdminModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notifikasi_admin';
    protected $primaryKey = 'notifikasi_adm_id';
    protected $fillable = [
        'user_id', 
        'kategori', 
        'permohonan_id',
        'pertanyaan_id',
        'pesan',  
        'sudah_dibaca', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    public function m_user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function t_permohonan()
    {
        return $this->belongsTo(PermohonanModel::class, 'permohonan_id', 'permohonan_id');
    }

    public function t_pertanyaan()
    {
        return $this->belongsTo(PertanyaanModel::class, 'pertanyaan_id', 'pertanyaan_id');
    }
}
