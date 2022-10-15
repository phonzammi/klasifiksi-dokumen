<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name'];

    public $timestamps = false;

    public const IS_DOSEN = 1;
    public const IS_MAHASISWA = 2;

    public function anggota()
    {
        return $this->hasMany(User::class);
    }
}
