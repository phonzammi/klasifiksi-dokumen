<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';
    protected $fillable = ['nama_dokumen', 'jenis_dokumen_id', 'user_id', 'lampiran'];

    protected $appends = ['lampiran_url'];

    public function getCreatedAtAttribute($datetime)
    {
        $created_at = new Carbon($datetime);
        return $created_at->isoFormat('dddd, D MMM Y. HH:MM');
    }

    public function getUpdatedAtAttribute($datetime)
    {
        $updated_at = new Carbon($datetime);
        return $updated_at->isoFormat('dddd, D MMM Y. HH:MM');
    }

    public function getLampiranUrlAttribute()
    {
        $lampiran_url = Storage::disk(config('public'))->url("lampiran/{$this->lampiran}");
        return $lampiran_url;
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(
            fn ($query) => $query->where('nama_dokumen', 'like', '%' . $term . '%')
                ->orWhere('lampiran', 'like', '%' . $term . '%')
        );
    }

    public function jenis_dokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id');
    }

    public function uploaded_by()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
