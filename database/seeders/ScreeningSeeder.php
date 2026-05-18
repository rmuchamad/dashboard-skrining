<?php

namespace Database\Seeders;

use App\Models\Respondent;
use App\Models\ScreeningSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScreeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh data dummy 30 responden dengan variasi gender, usia, dan kategori risiko.
        $namesMale = ['Andi', 'Budi', 'Charles', 'Dedi', 'Eko', 'Fajar', 'Gilang', 'Heri', 'Imam', 'Joko'];
        $namesFemale = ['Ani', 'Bella', 'Citra', 'Dewi', 'Eka', 'Farah', 'Gina', 'Hana', 'Intan', 'Julia'];

        for ($i = 0; $i < 30; $i++) {
            $isMale = $i % 2 === 0;
            $gender = $isMale ? 'male' : 'female';
            $name = $isMale
                ? $namesMale[array_rand($namesMale)]
                : $namesFemale[array_rand($namesFemale)];

            $age = rand(20, 65);

            $respondent = Respondent::create([
                'name' => $name . ' ' . ($i + 1),
                'gender' => $gender,
                'age' => $age,
                'marital_status' => rand(0, 1) ? 'Menikah' : 'Belum Menikah',
                'has_marriage_plan' => (bool)rand(0, 1),
                'is_disabled' => (bool)rand(0, 5) === 0,
                'is_pregnant' => $gender === 'female' ? (bool)rand(0, 4) === 0 : false,
                'work_unit' => ['HRD', 'Produksi', 'Marketing', 'Keuangan'][array_rand(['HRD', 'Produksi', 'Marketing', 'Keuangan'])],
            ]);

            // Skor risiko sederhana berdasarkan usia dan random faktor lain.
            $score = 0;
            if ($age >= 40) {
                $score += 2;
            }
            if (rand(0, 1)) {
                $score += 2; // misal perokok
            }
            if (rand(0, 4) === 0) {
                $score += 3; // misal batuk > 2 minggu / hepatitis B, dll
            }

            $category = 'Rendah';
            if ($score >= 3 && $score <= 5) {
                $category = 'Sedang';
            } elseif ($score > 5) {
                $category = 'Tinggi';
            }

            ScreeningSession::create([
                'respondent_id' => $respondent->id,
                'screened_at' => now()->subDays(rand(0, 30)),
                'risk_score' => $score,
                'risk_category' => $category,
            ]);
        }
    }
}
