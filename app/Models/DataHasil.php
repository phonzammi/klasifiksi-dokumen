<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataHasil extends Model
{
    use HasFactory;


    protected $table = 'data_hasil';

    protected $fillable = ['nama_dokumen', 'jenis_dokumen_id', 'nilai_kemiripan', 'data_training_id'];

    public $timestamps = false;

    public function jenis_dokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id');
    }

    public function data_training()
    {
        return $this->belongsTo(DataTraining::class, 'data_training_id');
    }
}
