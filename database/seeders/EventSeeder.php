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
        // Ambil admin pertama (PASTI ADA dari UsersTableSeeder)
        $admin = User::whereIn('role', ['admin', 'super_admin'])->first();

        if (!$admin) {
            throw new \Exception('Admin user not found. Please seed users first.');
        }

        Event::updateOrCreate(
            ['name' => 'Petra Tech Talk 2025'],
            [
                'description' => 'Seminar Petra Tech Talk',
                'image' => 'events/poster-default.jpg',
                'date' => Carbon::now()->addDays(10)->toDateString(),
                'time' => '09:00',
                'location' => 'Auditorium Q',
                'slot_peserta' => 200,
                'skkk_points' => 5,
                'skkk_category' => 'A',
                'skkk_points_volunteer' => 5,
                'created_by' => $admin->id,
            ]
        );

        Event::updateOrCreate(
            ['name' => 'Petra Charity Day'],
            [
                'description' => 'Kegiatan sosial untuk masyarakat sekitar.',
                'image' => 'events/poster-default.jpg',
                'date' => Carbon::now()->addDays(20)->toDateString(),
                'time' => '08:00',
                'location' => 'Lapangan Tengah PCU',
                'slot_peserta' => 150,
                'skkk_points' => 15,
                'skkk_category' => 'D',
                'skkk_points_volunteer' => 7,
                'created_by' => $admin->id,
            ]
        );

        Event::updateOrCreate(
            ['name' => 'Petra Leadership Camp'],
            [
                'description' => 'Pelatihan kepemimpinan mahasiswa.',
                'image' => 'events/poster-default.jpg',
                'date' => Carbon::now()->addDays(30)->toDateString(),
                'time' => '07:30',
                'location' => 'Villa Trawas',
                'slot_peserta' => 50,
                'skkk_points' => 20,
                'skkk_category' => 'C',
                'skkk_points_volunteer' => 10,
                'created_by' => $admin->id,
            ]
        );
    }
}
