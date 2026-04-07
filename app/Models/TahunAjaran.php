<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function enrollments()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function pengampus()
    {
        return $this->hasMany(Pengampu::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public static function current()
    {
        return static::aktif()->latest('id')->first();
    }
}
