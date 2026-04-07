<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'legacy_wali_kelas_id',
        'nip',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_guru_id');
    }

    public function pengampus()
    {
        return $this->hasMany(Pengampu::class);
    }

    public function sesiAbsensis()
    {
        return $this->hasMany(SesiAbsensi::class);
    }
}
