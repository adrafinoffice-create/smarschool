<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiAbsensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_pelajaran_id',
        'guru_id',
        'tanggal',
        'topik',
        'started_at',
        'closed_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function detailAbsensi()
    {
        return $this->hasMany(DetailAbsensi::class);
    }
}
