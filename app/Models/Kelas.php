<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $fillable =
     [
     'nama_kelas',
     'tahun_ajaran_id',
     'wali_kelas_id',
     'wali_guru_id',
     ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
     public function waliKelas()
    {
        return $this->belongsTo(WaliKelas::class);
    }

    public function waliGuru()
    {
        return $this->belongsTo(Guru::class, 'wali_guru_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function enrollments()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function absensi()
        {
            return $this->hasMany(Absensi::class);
        }

    public function pengampus()
    {
        return $this->hasMany(Pengampu::class);
    }

    public function jadwalPelajarans()
    {
        return $this->hasManyThrough(JadwalPelajaran::class, Pengampu::class);
    }

}
