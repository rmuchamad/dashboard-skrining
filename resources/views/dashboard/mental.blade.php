@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-slate-800">Dashboard Kesehatan Mental</h2>
    <p class="mt-2 text-base text-slate-600 mb-5">Analisis indikator PHQ: kurang semangat, murung/depresi, cemas, dan sulit mengendalikan khawatir.</p>

    @include('partials.dashboard_filters')

    <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-3">
        <div class="rounded-lg bg-amber-400 p-5 text-center text-slate-900 shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-80">% indikasi depresi ringan</div>
            <div class="text-4xl font-bold tabular-nums">{{ $kpiDepresiRingan }}%</div>
        </div>
        <div class="rounded-lg bg-rose-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">% indikasi kecemasan tinggi</div>
            <div class="text-4xl font-bold tabular-nums">{{ $kpiKecemasanTinggi }}%</div>
        </div>
        <div class="rounded-lg bg-blue-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">Total sesi</div>
            <div class="text-4xl font-bold tabular-nums">{{ $sessionCount }}</div>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 border-b border-slate-100 pb-3 text-lg font-semibold text-slate-800">Distribusi Frekuensi Indikator PHQ</div>
        <canvas id="phqStackedBarChart" class="max-h-[360px]"></canvas>
    </div>
@endsection

@push('scripts')
    <script>
        const phqLabels = @json($stacked['labels']);
        const kurangSemangat = @json($stacked['kurangSemangat']);
        const murungDepresi = @json($stacked['murungDepresi']);
        const cemas = @json($stacked['cemas']);
        const khawatirSulit = @json($stacked['khawatirSulit']);

        new Chart(document.getElementById('phqStackedBarChart'), {
            type: 'bar',
            data: {
                labels: phqLabels,
                datasets: [
                    { label: 'Kurang semangat', data: kurangSemangat, backgroundColor: '#2563eb' },
                    { label: 'Murung / depresi', data: murungDepresi, backgroundColor: '#e11d48' },
                    { label: 'Cemas', data: cemas, backgroundColor: '#fbbf24' },
                    { label: 'Tidak bisa mengendalikan khawatir', data: khawatirSulit, backgroundColor: '#059669' }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true }
                }
            }
        });
    </script>
@endpush
