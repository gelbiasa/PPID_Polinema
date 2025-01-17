<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPertanyaanLanjutModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_pertanyaan_detail_lanjut';
    protected $primaryKey = 'detail_pertanyaan_lanjut_id';
    protected $fillable = [
        'pertanyaan_lanjut_id',
        'pertanyaan',
        'jawaban',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function t_pertanyaan_lanjut()
    {
        return $this->belongsTo(PertanyaanLanjutModel::class, 'pertanyaan_lanjut_id', 'pertanyaan_lanjut_id');
    }
}
