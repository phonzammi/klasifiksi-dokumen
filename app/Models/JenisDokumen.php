<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    use HasFactory;

    protected $table = 'jenis_dokumen';
    public $timestamps = false;

    protected $fillable = ['jenis_dokumen'];

    /**
     * The roles that belong to the JenisDocument
     *
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_jenis_dokumen', 'jenis_dokumen_id', 'role_id');
    }
}
