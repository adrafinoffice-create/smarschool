<?php

namespace App\Support;

use App\Models\JadwalPelajaran;
use Carbon\Carbon;

class JadwalAbsensiState
{
    public static function todayName(?Carbon $now = null): string
    {
        $now ??= now();

        return match ($now->dayOfWeekIso) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            default => 'Minggu',
        };
    }

    public static function forSchedule(JadwalPelajaran $jadwalPelajaran, ?Carbon $now = null): array
    {
        $now ??= now();
        $hariIni = self::todayName($now);
        $isToday = mb_strtolower($jadwalPelajaran->hari) === mb_strtolower($hariIni);
        $start = $now->copy()->setTimeFromTimeString((string) $jadwalPelajaran->jam_mulai);
        $end = $now->copy()->setTimeFromTimeString((string) $jadwalPelajaran->jam_selesai);

        $state = [
            'hari_ini' => $hariIni,
            'is_today' => $isToday,
            'start' => $start,
            'end' => $end,
            'can_take_attendance' => false,
            'label' => 'Belum aktif',
            'description' => 'Absensi hanya bisa dilakukan saat jadwal pelajaran sedang berlangsung.',
            'tone' => 'slate',
        ];

        if (! $jadwalPelajaran->is_active) {
            $state['label'] = 'Nonaktif';
            $state['description'] = 'Jadwal ini dinonaktifkan oleh admin.';
            $state['tone'] = 'rose';

            return $state;
        }

        if (! $isToday) {
            $state['label'] = 'Bukan jadwal hari ini';
            $state['description'] = "Absensi akan aktif saat {$jadwalPelajaran->hari}.";

            return $state;
        }

        if ($now->lt($start)) {
            $state['label'] = 'Menunggu jam pelajaran';
            $state['description'] = 'Absensi akan terbuka ketika jam pelajaran dimulai.';
            $state['tone'] = 'amber';

            return $state;
        }

        if ($now->gt($end)) {
            $state['label'] = 'Jadwal selesai';
            $state['description'] = 'Sesi absensi sudah melewati jam pelajaran.';
            $state['tone'] = 'slate';

            return $state;
        }

        $state['label'] = 'Sedang berlangsung';
        $state['description'] = 'Guru dapat melakukan scan QR dan menyimpan absensi pada sesi ini.';
        $state['tone'] = 'emerald';
        $state['can_take_attendance'] = true;

        return $state;
    }
}
