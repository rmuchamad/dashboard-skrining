@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-slate-800">Dashboard Aktivitas Fisik</h2>
    <p class="mt-2 text-base text-slate-600 mb-5">Ringkasan aktivitas fisik sedang dan berat beserta tren menit aktivitas per hari.</p>

    @include('partials.dashboard_filters')

    <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-2">
        <div class="rounded-lg bg-emerald-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">% cukup aktivitas fisik</div>
            <div class="text-4xl font-bold tabular-nums">{{ $cukupPercent }}%</div>
        </div>
        <div class="rounded-lg bg-rose-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">% kurang aktivitas</div>
            <div class="text-4xl font-bold tabular-nums">{{ $kurangPercent }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Aktivitas (Bar Chart)</div>
            <canvas id="activityBarChart" class="max-h-[320px]"></canvas>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Rata-rata Menit Aktivitas per Hari</div>
            <canvas id="activityLineChart" class="max-h-[320px]"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new Chart(document.getElementById('activityBarChart'), {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Jumlah',
                    data: @json($barData),
                    backgroundColor: '#2563eb'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('activityLineChart'), {
            type: 'line',
            data: {
                labels: @json($lineLabels),
                datasets: [{
                    label: 'Menit',
                    data: @json($lineData),
                    borderColor: '#14b8a6',
                    backgroundColor: 'rgba(20,184,166,0.15)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>
@endpush
