<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengampu_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pengampu()
    {
        return $this->belongsTo(Pengampu::class);
    }

    public function sesiAbsensis()
    {
        return $this->hasMany(SesiAbsensi::class);
    }
}
