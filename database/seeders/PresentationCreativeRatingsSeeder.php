<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PresentationCreativeRatingsSeeder extends Seeder
{
    public function run(): void
    {
        $raters = collect(range(1, 15))->map(function (int $number) {
            return User::updateOrCreate(
                ['email' => "umkm.demo{$number}@konekin.test"],
                [
                    'name' => sprintf('UMKM Demo %02d', $number),
                    'password' => Hash::make('password'),
                    'type' => 'umkm',
                    'city' => fake()->randomElement(['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Denpasar']),
                    'profile_photo' => 'https://ui-avatars.com/api/?name=' . urlencode("UMKM Demo {$number}") . '&background=2563EB&color=fff',
                ]
            );
        })->values();

        $creatives = [
            [
                'name' => 'Naya Prameswari',
                'email' => 'naya.prameswari@konekin.test',
                'creative_category' => 'Graphic Designer',
                'skills' => ['Branding', 'Logo Design', 'Adobe Illustrator', 'Typography'],
                'city' => 'Bandung',
                'target_five_star' => 15,
                'project_prefix' => 'Brand Refresh',
            ],
            [
                'name' => 'Raka Adiputra',
                'email' => 'raka.adiputra@konekin.test',
                'creative_category' => 'Video Editor',
                'skills' => ['Video Editing', 'Motion Graphics', 'Color Grading', 'After Effects'],
                'city' => 'Jakarta',
                'target_five_star' => 12,
                'project_prefix' => 'Video Campaign',
            ],
            [
                'name' => 'Mika Salsabila',
                'email' => 'mika.salsabila@konekin.test',
                'creative_category' => 'Social Media Specialist',
                'skills' => ['Content Planner', 'Copywriting', 'Analytics', 'TikTok Marketing'],
                'city' => 'Yogyakarta',
                'target_five_star' => 7,
                'project_prefix' => 'Social Media Sprint',
            ],
            [
                'name' => 'Arka Wicaksana',
                'email' => 'arka.wicaksana@konekin.test',
                'creative_category' => 'Full Stack Developer',
                'skills' => ['Laravel', 'React.js', 'REST API', 'MySQL'],
                'city' => 'Surabaya',
                'target_five_star' => 22,
                'project_prefix' => 'Web Platform Build',
            ],
        ];

        foreach ($creatives as $creativeData) {
            $creative = User::updateOrCreate(
                ['email' => $creativeData['email']],
                [
                    'name' => $creativeData['name'],
                    'password' => Hash::make('password'),
                    'type' => 'creative_worker',
                    'creative_category' => $creativeData['creative_category'],
                    'skills' => $creativeData['skills'],
                    'onboarding_completed' => true,
                    'city' => $creativeData['city'],
                    'bio' => 'Creative worker demo untuk kebutuhan presentasi Konekin.',
                    'profile_photo' => 'https://ui-avatars.com/api/?name=' . urlencode($creativeData['name']) . '&background=1E3A8A&color=fff',
                    'profile_border' => 'ocean',
                ]
            );

            $existingFiveStarCount = Rating::where('to_user_id', (string) $creative->getKey())
                ->where('rating', 5)
                ->count();

            $ratingsToCreate = max(0, $creativeData['target_five_star'] - $existingFiveStarCount);

            for ($index = 1; $index <= $ratingsToCreate; $index++) {
                $ratingNumber = $existingFiveStarCount + $index;
                $rater = $raters[($ratingNumber - 1) % $raters->count()];

                Rating::create([
                    'project_id' => null,
                    'project_title_snapshot' => sprintf('%s #%02d', $creativeData['project_prefix'], $ratingNumber),
                    'from_user_id' => (string) $rater->getKey(),
                    'to_user_id' => (string) $creative->getKey(),
                    'rating' => 5,
                    'comment' => fake()->randomElement([
                        'Hasilnya rapi, cepat, dan sangat sesuai brief.',
                        'Komunikasinya enak, revisi cepat, kualitasnya premium.',
                        'Karyanya membantu brand kami terlihat jauh lebih profesional.',
                        'Pengerjaan detail dan hasil akhir siap dipakai untuk campaign.',
                        'Sangat puas, kreatifnya terasa dan tetap tepat sasaran.',
                    ]),
                    'created_at' => now()->subDays($ratingNumber)->addMinutes(random_int(5, 240)),
                    'updated_at' => now()->subDays($ratingNumber)->addMinutes(random_int(245, 480)),
                ]);
            }
        }
    }
}
