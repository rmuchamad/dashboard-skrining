@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-slate-800">Dashboard Perilaku Merokok</h2>
    <p class="mt-2 text-base text-slate-600 mb-5">Ringkasan jenis rokok, lama merokok, dan konsumsi batang per hari.</p>

    @include('partials.dashboard_filters')

    <div class="mb-6 grid grid-cols-1 gap-3 md:grid-cols-3">
        <div class="rounded-lg bg-blue-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">Jumlah perokok aktif</div>
            <div class="text-4xl font-bold tabular-nums">{{ $perokok }}</div>
        </div>
        <div class="rounded-lg bg-emerald-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">Rata-rata batang/hari</div>
            <div class="text-4xl font-bold tabular-nums">{{ $avgCigPerDay }}</div>
        </div>
        <div class="rounded-lg bg-amber-400 p-5 text-center text-slate-900 shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-80">Rata-rata lama merokok</div>
            <div class="text-4xl font-bold tabular-nums">{{ $avgLamaMerokok }} th</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Jenis Rokok</div>
            <canvas id="jenisRokokPieChart" class="max-h-[260px]"></canvas>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Histogram Lama Merokok</div>
            <canvas id="lamaMerokokHistChart" class="max-h-[260px]"></canvas>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Batang Rokok per Hari</div>
            <canvas id="cigsPerDayChart" class="max-h-[260px]"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        new Chart(document.getElementById('jenisRokokPieChart'), {
            type: 'pie',
            data: {
                labels: @json($jenisPie['labels']),
                datasets: [{
                    data: @json($jenisPie['data']),
                    backgroundColor: ['#2563eb', '#14b8a6', '#7c3aed']
                }]
            },
            options: { responsive: true }
        });

        new Chart(document.getElementById('lamaMerokokHistChart'), {
            type: 'bar',
            data: {
                labels: @json($bins),
                datasets: [{
                    label: 'Jumlah',
                    data: @json($hist),
                    backgroundColor: '#fbbf24'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('cigsPerDayChart'), {
            type: 'bar',
            data: {
                labels: @json($cigsLabels),
                datasets: [{
                    label: 'Jumlah',
                    data: @json($cigsData),
                    backgroundColor: '#e11d48'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>
@endpush
