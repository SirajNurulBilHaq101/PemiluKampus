<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $bemEvent = Event::where('name', 'like', '%BEM%')->first();
        $himtiEvent = Event::where('name', 'like', '%Himpunan%')->first();

        // Kandidat BEM
        Candidate::create([
            'event_id'         => $bemEvent->id,
            'name'             => 'Andi Wijaya',
            'candidate_number' => 1,
            'vision'           => 'Mewujudkan BEM yang transparan, inovatif, dan berdampak nyata bagi seluruh mahasiswa.',
            'mission'          => "1. Meningkatkan transparansi keuangan BEM melalui laporan publik bulanan\n2. Mengadakan minimal 12 kegiatan pengembangan soft-skill per semester\n3. Membangun platform digital aspirasi mahasiswa\n4. Menjalin kerja sama dengan organisasi kampus lain",
        ]);

        Candidate::create([
            'event_id'         => $bemEvent->id,
            'name'             => 'Putri Rahayu',
            'candidate_number' => 2,
            'vision'           => 'Menciptakan ekosistem kampus yang inklusif, kolaboratif, dan berwawasan global.',
            'mission'          => "1. Membuka akses program pertukaran mahasiswa antar universitas\n2. Mengembangkan program mentoring senior-junior\n3. Mendorong kegiatan riset dan publikasi mahasiswa\n4. Menyediakan ruang diskusi terbuka setiap minggu\n5. Mengadvokasi hak-hak mahasiswa secara aktif",
        ]);

        Candidate::create([
            'event_id'         => $bemEvent->id,
            'name'             => 'Fajar Nugroho',
            'candidate_number' => 3,
            'vision'           => 'BEM sebagai rumah bersama yang mendengar, bergerak, dan memberi perubahan.',
            'mission'          => "1. Membentuk tim advokasi mahasiswa yang responsif 24 jam\n2. Meningkatkan kualitas acara BEM dengan evaluasi berbasis feedback\n3. Mendorong keberlanjutan program kerja antar periode",
        ]);

        // Kandidat Himpunan Informatika
        Candidate::create([
            'event_id'         => $himtiEvent->id,
            'name'             => 'Raka Aditya',
            'candidate_number' => 1,
            'vision'           => 'Himpunan Informatika sebagai wadah pengembangan teknologi dan komunitas yang solid.',
            'mission'          => "1. Menyelenggarakan hackathon tahunan tingkat nasional\n2. Membuat program belajar coding bersama untuk mahasiswa baru\n3. Membangun portofolio digital bersama untuk anggota\n4. Mengundang praktisi industri IT untuk sharing session bulanan",
        ]);

        Candidate::create([
            'event_id'         => $himtiEvent->id,
            'name'             => 'Nadia Safitri',
            'candidate_number' => 2,
            'vision'           => 'Membangun Himpunan Informatika yang kreatif, produktif, dan siap menghadapi era digital.',
            'mission'          => "1. Membentuk tim open-source untuk proyek komunitas\n2. Mengadakan workshop UI/UX, AI, dan cloud computing setiap bulan\n3. Menjalin partnership dengan startup-startup lokal\n4. Membuat newsletter digital mingguan tentang tren teknologi",
        ]);
    }
}
