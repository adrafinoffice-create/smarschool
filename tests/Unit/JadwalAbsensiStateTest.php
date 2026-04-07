<?php

namespace Tests\Unit;

use App\Models\JadwalPelajaran;
use App\Support\JadwalAbsensiState;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class JadwalAbsensiStateTest extends TestCase
{
    public function test_schedule_is_active_when_current_time_is_within_the_session(): void
    {
        $jadwal = new JadwalPelajaran([
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:40:00',
            'is_active' => true,
        ]);

        $state = JadwalAbsensiState::forSchedule($jadwal, Carbon::create(2026, 4, 6, 8, 30, 0));

        $this->assertTrue($state['can_take_attendance']);
        $this->assertSame('Sedang berlangsung', $state['label']);
    }

    public function test_schedule_is_not_active_before_the_session_starts(): void
    {
        $jadwal = new JadwalPelajaran([
            'hari' => 'Senin',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '11:30:00',
            'is_active' => true,
        ]);

        $state = JadwalAbsensiState::forSchedule($jadwal, Carbon::create(2026, 4, 6, 9, 30, 0));

        $this->assertFalse($state['can_take_attendance']);
        $this->assertSame('Menunggu jam pelajaran', $state['label']);
    }

    public function test_schedule_is_not_active_on_a_different_day(): void
    {
        $jadwal = new JadwalPelajaran([
            'hari' => 'Selasa',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:30:00',
            'is_active' => true,
        ]);

        $state = JadwalAbsensiState::forSchedule($jadwal, Carbon::create(2026, 4, 6, 8, 15, 0));

        $this->assertFalse($state['can_take_attendance']);
        $this->assertSame('Bukan jadwal hari ini', $state['label']);
    }
}
