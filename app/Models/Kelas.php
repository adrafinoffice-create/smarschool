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
     'wali_kelas_id'
     ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
     public function waliKelas()
    {
        return $this->belongsTo(WaliKelas::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function absensi()
        {
            return $this->hasMany(Absensi::class);
        }


}
