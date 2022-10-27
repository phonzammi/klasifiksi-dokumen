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
        return $this->belongsToMany(Role::class, 'role_jenis_dokumen', 'jenis_dokumen_id', 'role_id')->orderByPivot('role_id')
            ->withPivot('view', 'upload', 'download');
    }

    public function roles_can_view()
    {
        return $this->belongsToMany(Role::class, 'role_jenis_dokumen', 'jenis_dokumen_id', 'role_id')
            ->wherePivot('view', 1);
    }

    public function roles_can_upload()
    {
        return $this->belongsToMany(Role::class, 'role_jenis_dokumen', 'jenis_dokumen_id', 'role_id')
            ->wherePivot('upload', 1);
    }

    public function roles_can_download()
    {
        return $this->belongsToMany(Role::class, 'role_jenis_dokumen', 'jenis_dokumen_id', 'role_id')
            ->wherePivot('download', 1);
    }

    public function documents()
    {
        return $this->hasMany(Dokumen::class, 'jenis_dokumen_id');
    }
}
