<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil Admin untuk kolom created_by
        $admin = User::whereIn('role', ['admin', 'super_admin'])->first();

        if (!$admin) {
            // Fallback: Jika admin belum ada, buat user dummy agar seeder tidak error
            $admin = User::factory()->create(['role' => 'admin']);
        }

        // 2. Buat Event dengan Gambar dari S3
        $events = [
            [
                'name' => 'Petra Tech Talk 2025',
                'description' => 'Seminar teknologi masa depan.',
                // PERHATIKAN INI: Cukup nama folder dan nama file di S3
                'image' => 'events/poster-default.jpg', 
                'date' => Carbon::now()->addDays(10)->toDateString(),
                'time' => '09:00',
                'location' => 'Auditorium Q',
                'slot_peserta' => 200,
                'skkk_points' => 10,
                'skkk_category' => 'Penalaran',
                'skkk_points_volunteer' => 5,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Petra Charity Day',
                'description' => 'Berbagi kasih dengan sesama.',
                'image' => 'events/poster-default.jpg', // Pakai gambar yang sama (karena ada di S3)
                'date' => Carbon::now()->addDays(20)->toDateString(),
                'time' => '08:00',
                'location' => 'Lapangan Hijau',
                'slot_peserta' => 100,
                'skkk_points' => 15,
                'skkk_category' => 'Pengabdian Masyarakat',
                'skkk_points_volunteer' => 10,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(['name' => $event['name']], $event);
        }
    }
}