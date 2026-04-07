<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAbsensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'sesi_absensi_id',
        'siswa_id',
        'status',
        'keterangan',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function sesiAbsensi()
    {
        return $this->belongsTo(SesiAbsensi::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
