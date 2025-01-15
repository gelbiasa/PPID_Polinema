<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPertanyaanModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 't_pertanyaan_detail';
    protected $primaryKey = 'detail_pertanyaan_id';
    protected $fillable = [
        'pertanyaan_id', 
        'pertanyaan', 
        'jawaban',  
        'created_at', 
        'updated_at'
    ];

    public function m_user()
    {
        return $this->belongsTo(PertanyaanModel::class, 'pertanyaan_id', 'pertanyaan_id');
    }
}