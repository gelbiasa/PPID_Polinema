<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PertanyaanModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_pertanyaan';
    protected $primaryKey = 'pertanyaan_id';
    protected $fillable = [
        'user_id',
        'kategori',
        'status_pemohon',
        'status',
        'alasan_penolakan',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function m_user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function t_pertanyaan_detail()
    {
        return $this->hasMany(DetailPertanyaanModel::class, 'pertanyaan_id', 'pertanyaan_id');
    }
}
