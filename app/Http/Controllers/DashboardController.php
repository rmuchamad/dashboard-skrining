<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use App\Models\ScreeningSession;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function applyRespondentFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('gender')) {
            $query->where('gender', $request->string('gender'));
        }

        if ($request->filled('min_age')) {
            $query->where('age', '>=', (int)$request->input('min_age'));
        }

        if ($request->filled('max_age')) {
            $query->where('age', '<=', (int)$request->input('max_age'));
        }

        if ($request->has('is_disabled') && $request->input('is_disabled') !== '') {
            $query->where('is_disabled', (int)$request->input('is_disabled'));
        }

        return $query;
    }

    private function filteredRespondents(Request $request): Builder
    {
        return $this->applyRespondentFilters(Respondent::query(), $request);
    }

    private function filteredSessions(Request $request): Builder
    {
        return ScreeningSession::query()->whereHas('respondent', function (Builder $q) use ($request) {
            $this->applyRespondentFilters($q, $request);
        });
    }

    private function sessionsCount(Request $request, Closure $modifier): int
    {
        $q = $this->filteredSessions($request);
        $modifier($q);
        return (int)$q->count();
    }

    public function overview(Request $request)
    {
        $respondentsQuery = $this->filteredRespondents($request);
        $totalRespondents = (int)$respondentsQuery->count();

        $sessionCount = (int)$this->filteredSessions($request)->count();
        $highRiskCount = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_category', 'Tinggi'));
        $highRiskPercentage = $sessionCount > 0 ? round(($highRiskCount / max($sessionCount, 1)) * 100, 1) : 0;

        // Mapping sementara berbasis risk_score dari seeder (agar dashboard terlihat dulu).
        $activeSmokers = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 2));
        $tbSymptomsPercentage = $sessionCount > 0 ? round(($this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 4)) / $sessionCount) * 100, 1) : 0;
        $cancerRiskPercentage = $tbSymptomsPercentage;
        $mentalRiskPercentage = $sessionCount > 0 ? round(($this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 3)) / $sessionCount) * 100, 1) : 0;

        $male = (clone $respondentsQuery)->where('gender', 'male')->count();
        $female = (clone $respondentsQuery)->where('gender', 'female')->count();

        $genderChart = [
            'labels' => ['Laki-laki', 'Perempuan'],
            'data' => [$male, $female],
        ];

        return view('dashboard.overview', compact(
            'totalRespondents',
            'highRiskPercentage',
            'activeSmokers',
            'tbSymptomsPercentage',
            'cancerRiskPercentage',
            'mentalRiskPercentage',
            'genderChart',
        ));
    }

    public function riskFactors(Request $request)
    {
        return $this->buildRiskFactorsView($request);
    }

    public function mentalHealth(Request $request)
    {
        return $this->buildMentalView($request);
    }

    public function smoking(Request $request)
    {
        return $this->buildSmokingView($request);
    }

    public function physicalActivity(Request $request)
    {
        return $this->buildActivityView($request);
    }

    public function riskScoring(Request $request)
    {
        return $this->buildRiskScoringView($request);
    }
    
    private function buildRiskFactorsView(Request $request)
    {
        $sessionCount = (int)$this->filteredSessions($request)->count();

        $batukGt2 = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 5));
        $batukLt2 = $this->sessionsCount($request, fn (Builder $q) => $q->whereBetween('risk_score', [3, 4]));
        $tidakBatuk = max($sessionCount - ($batukGt2 + $batukLt2), 0);

        $batukPie = [
            'labels' => ['Batuk > 2 minggu', 'Batuk < 2 minggu', 'Tidak batuk'],
            'data' => [$batukGt2, $batukLt2, $tidakBatuk],
        ];

        $riwayatKankerYa = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 4));
        $riwayatKankerTidak = max($sessionCount - $riwayatKankerYa, 0);
        $cancerPie = [
            'labels' => ['Riwayat keluarga kanker', 'Tidak ada riwayat'],
            'data' => [$riwayatKankerYa, $riwayatKankerTidak],
        ];

        $perokok = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 2));
        $nonPerokok = max($sessionCount - $perokok, 0);

        $hepatitisBar = [
            'labels' => [
                'Hepatitis B positif',
                'Riwayat keluarga HB',
                'Seks berisiko',
                'Riwayat transfusi',
                'Riwayat hemodialisis',
                'Narkoba suntik',
                'ODHIV',
                'Riwayat Hepatitis C',
                'Kolesterol tinggi',
            ],
            'data' => [
                $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 5)->whereHas('respondent', fn (Builder $r) => $r->where('gender', 'male'))),
                $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 4)),
                $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 3)->whereHas('respondent', fn (Builder $r) => $r->where('gender', 'female'))),
                $this->sessionsCount($request, fn (Builder $q) => $q->whereBetween('risk_score', [2, 3])),
                $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 6)),
                $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 6)->whereHas('respondent', fn (Builder $r) => $r->where('is_disabled', 1))),
                $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 5)),
                $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 4)->whereHas('respondent', fn (Builder $r) => $r->where('is_disabled', 1))),
                $this->sessionsCount($request, fn (Builder $q) => $q->whereHas('respondent', fn (Builder $r) => $r->where('age', '>=', 50))),
            ],
        ];

        $kpiTotalFaktor = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 3));
        $kpiPercentKeluargaKanker = $sessionCount > 0 ? round(($riwayatKankerYa / $sessionCount) * 100, 1) : 0;
        $kpiPercentPerokok = $sessionCount > 0 ? round(($perokok / $sessionCount) * 100, 1) : 0;
        $kpiJumlahTbPotensial = $batukGt2 + $batukLt2;

        return view('dashboard.risk_factors', compact(
            'sessionCount',
            'batukPie',
            'cancerPie',
            'perokok',
            'nonPerokok',
            'hepatitisBar',
            'kpiTotalFaktor',
            'kpiPercentKeluargaKanker',
            'kpiPercentPerokok',
            'kpiJumlahTbPotensial',
        ));
    }

    private function buildMentalView(Request $request)
    {
        $sessionCount = (int)$this->filteredSessions($request)->count();

        $countTidak = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '<', 2));
        $countLt1 = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', 2));
        $countMinggu = $this->sessionsCount($request, fn (Builder $q) => $q->whereBetween('risk_score', [3, 4]));
        $countHampir = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 5));

        $stacked = [
            'labels' => ['Tidak sama sekali', '<1 minggu', '1 minggu', 'Hampir setiap hari'],
            'kurangSemangat' => [$countTidak, $countLt1, $countMinggu, $countHampir],
            'murungDepresi' => [$countTidak, $countLt1, $countMinggu, $countHampir],
            'cemas' => [$countTidak, $countLt1, $countMinggu, $countHampir],
            'khawatirSulit' => [$countTidak, $countLt1, $countMinggu, $countHampir],
        ];

        $kpiDepresiRingan = $sessionCount > 0
            ? round(($this->sessionsCount($request, fn (Builder $q) => $q->whereBetween('risk_score', [3, 4])) / $sessionCount) * 100, 1)
            : 0;
        $kpiKecemasanTinggi = $sessionCount > 0
            ? round(($this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 4)) / $sessionCount) * 100, 1)
            : 0;

        return view('dashboard.mental', compact(
            'sessionCount',
            'stacked',
            'kpiDepresiRingan',
            'kpiKecemasanTinggi',
        ));
    }

    private function buildSmokingView(Request $request)
    {
        $perokok = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_score', '>=', 2));
        $sessionCount = (int)$this->filteredSessions($request)->count();

        $avgAge = (float)$this->filteredRespondents($request)->avg('age');
        $avgCigPerDay = $avgAge > 0 ? round(max(1, ($avgAge - 20) / 2), 1) : 0;
        $avgLamaMerokok = $avgAge > 0 ? round(max(1, ($avgAge - 18)), 1) : 0;

        $jenisRokokConventional = (int)round($perokok * 0.45);
        $jenisRokokVape = (int)round($perokok * 0.25);
        $jenisRokokKeduanya = max($perokok - ($jenisRokokConventional + $jenisRokokVape), 0);

        $jenisPie = [
            'labels' => ['Rokok konvensional', 'Vape', 'Keduanya'],
            'data' => [$jenisRokokConventional, $jenisRokokVape, $jenisRokokKeduanya],
        ];

        $bins = ['<10 th', '10-14', '15-19', '20-29', '30+'];
        $hist = [
            (int)max(0, round($perokok * 0.20)),
            (int)max(0, round($perokok * 0.25)),
            (int)max(0, round($perokok * 0.30)),
            (int)max(0, round($perokok * 0.15)),
            (int)max(0, $perokok - (round($perokok * 0.20) + round($perokok * 0.25) + round($perokok * 0.30) + round($perokok * 0.15))),
        ];

        $cigsLabels = ['2', '5', '10', '15', '20+'];
        $cigsData = [
            (int)round($perokok * 0.15),
            (int)round($perokok * 0.25),
            (int)round($perokok * 0.30),
            (int)round($perokok * 0.20),
            (int)max(0, $perokok - (round($perokok * 0.15) + round($perokok * 0.25) + round($perokok * 0.30) + round($perokok * 0.20))),
        ];

        return view('dashboard.smoking', compact(
            'sessionCount',
            'perokok',
            'avgCigPerDay',
            'avgLamaMerokok',
            'jenisPie',
            'bins',
            'hist',
            'cigsLabels',
            'cigsData',
        ));
    }

    private function buildActivityView(Request $request)
    {
        $respondentCount = (int)$this->filteredRespondents($request)->count();
        $sessionCount = (int)$this->filteredSessions($request)->count();

        $avgAge = (float)$this->filteredRespondents($request)->avg('age');
        $minutesAvg = $avgAge > 0 ? round(20 + ($avgAge - 20) * 0.8, 0) : 0;

        $cukup = $this->filteredSessions($request)->where('risk_score', '<', 4)->count();
        $kurang = max($sessionCount - $cukup, 0);
        $cukupPercent = ($cukup + $kurang) > 0 ? round(($cukup / max(($cukup + $kurang), 1)) * 100, 1) : 0;
        $kurangPercent = 100 - $cukupPercent;

        $labels = [
            'Aktivitas rumah tangga',
            'Aktivitas kerja',
            'Aktivitas perjalanan',
            'Olahraga sedang',
            'Aktivitas berat kerja',
            'Olahraga berat',
        ];

        $barData = [
            (int)round($respondentCount * 0.25),
            (int)round($respondentCount * 0.20),
            (int)round($respondentCount * 0.15),
            (int)round($respondentCount * 0.10),
            (int)round($respondentCount * 0.20),
            (int)round($respondentCount * 0.10),
        ];

        $lineLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $lineData = [
            (int)round($minutesAvg * 0.9),
            (int)round($minutesAvg * 1.0),
            (int)round($minutesAvg * 0.95),
            (int)round($minutesAvg * 1.05),
            (int)round($minutesAvg * 1.1),
            (int)round($minutesAvg * 0.85),
            (int)round($minutesAvg * 1.0),
        ];

        return view('dashboard.activity', compact(
            'cukupPercent',
            'kurangPercent',
            'labels',
            'barData',
            'lineLabels',
            'lineData',
        ));
    }

    private function buildRiskScoringView(Request $request)
    {
        $sessionCount = (int)$this->filteredSessions($request)->count();
        $low = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_category', 'Rendah'));
        $med = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_category', 'Sedang'));
        $high = $this->sessionsCount($request, fn (Builder $q) => $q->where('risk_category', 'Tinggi'));

        $highRiskPercentage = $sessionCount > 0 ? round(($high / $sessionCount) * 100, 1) : 0;
        $gaugeValue = min(100, max(0, $highRiskPercentage));

        $pie = [
            'labels' => ['Risiko Rendah', 'Risiko Sedang', 'Risiko Tinggi'],
            'data' => [$low, $med, $high],
        ];

        return view('dashboard.risk_scoring', compact(
            'highRiskPercentage',
            'gaugeValue',
            'pie',
        ));
    }
}
