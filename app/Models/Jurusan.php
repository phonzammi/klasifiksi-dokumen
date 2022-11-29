<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    protected $table = 'jurusan';
    public $timestamps = false;

    public function semua_prodi()
    {
        return $this->hasMany(Prodi::class, 'jurusan_id');
    }
}
