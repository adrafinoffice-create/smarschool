<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $fillable = [
        'kelas_id',
        'siswa_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan'
    ];

    public function Walikelas()
    {
        return $this->belongsTo(WaliKelas::class);
    }

     public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
