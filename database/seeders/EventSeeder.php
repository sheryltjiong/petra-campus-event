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
            // Fallback: Buat user dummy jika admin tidak ada
            $admin = User::factory()->create(['role' => 'admin']);
        }

        // 2. Data 7 Event Sesuai Poster yang Diupload
        $events = [
            // Poster 1: Who's The Next Leader (OSIS/Leadership)
            [
                'name' => 'Student Leadership Election 2025',
                'description' => 'Pemilihan calon ketua dan wakil ketua organisasi periode baru. Who is the next leader?',
                'image' => 'events/poster1.jpeg', // Ekstensi .jpeg
                'date' => Carbon::now()->addDays(7)->toDateString(),
                'time' => '08:00',
                'location' => 'Main Hall',
                'slot_peserta' => 500,
                'skkk_points' => 10,
                'skkk_category' => 'Organisasi',
                'skkk_points_volunteer' => 10,
                'created_by' => $admin->id,
            ],
            // Poster 2: Open Recruitment DKV Students
            [
                'name' => 'Open Recruitment HIMA DKV',
                'description' => 'Pendaftaran pengurus baru Himpunan Mahasiswa DKV. Are you next?',
                'image' => 'events/poster2.jpeg', // Ekstensi .jpeg
                'date' => Carbon::now()->addDays(10)->toDateString(),
                'time' => '10:00',
                'location' => 'Gedung P (DKV)',
                'slot_peserta' => 100,
                'skkk_points' => 5,
                'skkk_category' => 'Organisasi',
                'skkk_points_volunteer' => 5,
                'created_by' => $admin->id,
            ],
            // Poster 3: Open Recruitment Volunteers P2S
            [
                'name' => 'Volunteer Recruitment P2S PJSD',
                'description' => 'Dicari volunteer untuk Sie Acara, Humas, Dekdok, dan Perkap acara P2S Hima PJSD.',
                'image' => 'events/poster3.jpeg', // Ekstensi .jpeg
                'date' => Carbon::now()->addDays(14)->toDateString(),
                'time' => '09:00',
                'location' => 'Ruang Rapat Hima',
                'slot_peserta' => 50,
                'skkk_points' => 15,
                'skkk_category' => 'Pengabdian Masyarakat',
                'skkk_points_volunteer' => 20,
                'created_by' => $admin->id,
            ],
            // Poster 4: Bye Bye Plastic
            [
                'name' => 'Workshop: Bye Bye Plastic',
                'description' => 'Mari jaga lingkungan kita dengan membuang sampah pada tempatnya dan kurangi plastik.',
                'image' => 'events/poster4.jpeg', // Ekstensi .jpeg
                'date' => Carbon::now()->addDays(20)->toDateString(),
                'time' => '07:30',
                'location' => 'Lapangan Hijau',
                'slot_peserta' => 200,
                'skkk_points' => 10,
                'skkk_category' => 'Lingkungan',
                'skkk_points_volunteer' => 10,
                'created_by' => $admin->id,
            ],
            // Poster 5: Green Revolution
            [
                'name' => 'Green Revolution Campaign',
                'description' => 'Wujudkan kelestarian alam bersama generasi penerus bangsa. Kegiatan menanam bunga dan pohon.',
                'image' => 'events/poster5.jpeg', // Ekstensi .jpeg
                'date' => Carbon::now()->addDays(25)->toDateString(),
                'time' => '08:00',
                'location' => 'Hutan Kota Kampus',
                'slot_peserta' => 150,
                'skkk_points' => 15,
                'skkk_category' => 'Lingkungan',
                'skkk_points_volunteer' => 10,
                'created_by' => $admin->id,
            ],
            // Poster 6: Buku Adalah Jendela Dunia
            [
                'name' => 'Pekan Literasi: Jendela Dunia',
                'description' => 'Melalui buku kita dapat menjelajahi tempat dan budaya berbeda. Mari tingkatkan minat baca.',
                'image' => 'events/poster6.jpg', // PERHATIKAN: Ini .jpg (sesuai upload Anda)
                'date' => Carbon::now()->addDays(30)->toDateString(),
                'time' => '11:00',
                'location' => 'Perpustakaan Pusat',
                'slot_peserta' => 300,
                'skkk_points' => 5,
                'skkk_category' => 'Penalaran',
                'skkk_points_volunteer' => 5,
                'created_by' => $admin->id,
            ],
            // Poster 7: Save Our Planet
            [
                'name' => 'Save Our Planet Action',
                'description' => 'Aksi nyata menyelamatkan bumi. Kegiatan edukasi lingkungan hidup dan pelestarian satwa.',
                'image' => 'events/poster7.jpeg', // Ekstensi .jpeg
                'date' => Carbon::now()->addDays(35)->toDateString(),
                'time' => '15:00',
                'location' => 'Auditorium Utama',
                'slot_peserta' => 250,
                'skkk_points' => 10,
                'skkk_category' => 'Lingkungan',
                'skkk_points_volunteer' => 10,
                'created_by' => $admin->id,
            ],
        ];

        // 3. Simpan ke Database
        foreach ($events as $event) {
            Event::updateOrCreate(['name' => $event['name']], $event);
        }
    }
}