<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name'];

    public const IS_DOSEN = 2;
    public const IS_MAHASISWA = 2;
}
