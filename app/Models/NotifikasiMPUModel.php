<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotifikasiMPUModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notifikasi_mpu';
    protected $primaryKey = 'notifikasi_mpu_id';
    protected $fillable = [
        'user_id', 
        'kategori', 
        'permohonan_lanjut_id',
        'pertanyaan_lanjut_id',
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

    public function t_permohonan_lanjut()
    {
        return $this->belongsTo(PermohonanLanjutModel::class, 'permohonan_lanjut_id', 'permohonan_lanjut_id');
    }

    public function t_pertanyaan_lanjut()
    {
        return $this->belongsTo(PertanyaanLanjutModel::class, 'pertanyaan_lanjut_id', 'pertanyaan_lanjut_id');
    }
}
