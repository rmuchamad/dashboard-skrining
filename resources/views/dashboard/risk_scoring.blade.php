@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-slate-800">Dashboard Risk Scoring</h2>
    <p class="mt-2 text-base text-slate-600 mb-5">Gauge dan distribusi kategori risiko berdasarkan hasil scoring otomatis.</p>

    @include('partials.dashboard_filters')

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm lg:col-span-5">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Gauge Risiko</div>
            <div class="mb-4 text-center">
                <div class="text-sm text-slate-600">Persentase Risiko Tinggi</div>
                <div class="text-5xl font-bold tabular-nums text-rose-600">{{ $highRiskPercentage }}%</div>
                <div class="text-sm text-slate-600">Risk Index</div>
            </div>
            <canvas id="riskGaugeChart" class="max-h-[260px]"></canvas>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm lg:col-span-7">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Distribusi Risiko</div>
            <canvas id="riskPieChart" class="max-h-[320px]"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const gaugeValue = @json($gaugeValue);
        new Chart(document.getElementById('riskGaugeChart'), {
            type: 'doughnut',
            data: {
                labels: ['Risk', 'Sisa'],
                datasets: [{
                    data: [gaugeValue, 100 - gaugeValue],
                    backgroundColor: ['#e11d48', '#e2e8f0'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });

        new Chart(document.getElementById('riskPieChart'), {
            type: 'pie',
            data: {
                labels: @json($pie['labels']),
                datasets: [{
                    data: @json($pie['data']),
                    backgroundColor: ['#059669', '#fbbf24', '#e11d48']
                }]
            },
            options: { responsive: true }
        });
    </script>
@endpush
