@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-slate-800">Overview Skrining CKG</h2>
    <p class="mt-2 text-base text-slate-600 mb-5">Ringkasan kondisi responden serta indikator risiko utama.</p>

    @include('partials.dashboard_filters')

    <div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-6">
        <div class="rounded-lg bg-blue-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase tracking-wide opacity-90">Total Responden</div>
            <div class="text-4xl font-bold tabular-nums">{{ $totalRespondents }}</div>
        </div>
        <div class="rounded-lg bg-rose-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase tracking-wide opacity-90">% Risiko Tinggi</div>
            <div class="text-4xl font-bold tabular-nums">{{ $highRiskPercentage }}%</div>
        </div>
        <div class="rounded-lg bg-slate-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase tracking-wide opacity-90">Perokok Aktif</div>
            <div class="text-4xl font-bold tabular-nums">{{ $activeSmokers }}</div>
        </div>
        <div class="rounded-lg bg-amber-400 p-5 text-center text-slate-900 shadow-sm">
            <div class="text-sm font-semibold uppercase tracking-wide opacity-80">% Gejala TB</div>
            <div class="text-4xl font-bold tabular-nums">{{ $tbSymptomsPercentage }}%</div>
        </div>
        <div class="rounded-lg bg-blue-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase tracking-wide opacity-90">% Risiko Kanker</div>
            <div class="text-4xl font-bold tabular-nums">{{ $cancerRiskPercentage }}%</div>
        </div>
        <div class="rounded-lg bg-emerald-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase tracking-wide opacity-90">% Risiko Mental</div>
            <div class="text-4xl font-bold tabular-nums">{{ $mentalRiskPercentage }}%</div>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 border-b border-slate-100 pb-3 text-lg font-semibold text-slate-800">Responden berdasarkan Jenis Kelamin</div>
        <div class="min-h-[280px]">
            <canvas id="genderChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const genderCtx = document.getElementById('genderChart');
        new Chart(genderCtx, {
            type: 'bar',
            data: {
                labels: @json($genderChart['labels']),
                datasets: [{
                    label: 'Jumlah',
                    data: @json($genderChart['data']),
                    backgroundColor: ['#2563eb', '#e11d48']
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endpush
