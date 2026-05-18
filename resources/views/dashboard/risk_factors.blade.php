@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-slate-800">Dashboard Faktor Risiko Penyakit</h2>
    <p class="mt-2 text-base text-slate-600 mb-5">Ringkasan indikator risiko kanker usus, TB, dan penyakit hati.</p>

    @include('partials.dashboard_filters')

    <div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-4">
        <div class="rounded-lg bg-rose-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">% Perokok</div>
            <div class="text-4xl font-bold tabular-nums">{{ $kpiPercentPerokok }}%</div>
        </div>
        <div class="rounded-lg bg-amber-400 p-5 text-center text-slate-900 shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-80">% Riwayat keluarga kanker</div>
            <div class="text-4xl font-bold tabular-nums">{{ $kpiPercentKeluargaKanker }}%</div>
        </div>
        <div class="rounded-lg bg-slate-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">Gejala TB potensial</div>
            <div class="text-4xl font-bold tabular-nums">{{ $kpiJumlahTbPotensial }}</div>
        </div>
        <div class="rounded-lg bg-blue-600 p-5 text-center text-white shadow-sm">
            <div class="text-sm font-semibold uppercase opacity-90">&gt;=1 faktor risiko</div>
            <div class="text-4xl font-bold tabular-nums">{{ $kpiTotalFaktor }}</div>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Risiko TB (Kategori Batuk)</div>
            <canvas id="batukPieChart" class="max-h-[260px]"></canvas>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Risiko Kanker Usus (Riwayat Keluarga)</div>
            <canvas id="cancerPieChart" class="max-h-[260px]"></canvas>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Perokok vs Non-perokok</div>
            <canvas id="perokokBarChart" class="max-h-[260px]"></canvas>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-3 border-b border-slate-100 pb-2 text-lg font-semibold text-slate-800">Faktor Risiko Hepatitis</div>
        <canvas id="hepatitisBarChart" class="max-h-[340px]"></canvas>
    </div>
@endsection

@push('scripts')
    <script>
        const batukLabels = @json($batukPie['labels']);
        const batukData = @json($batukPie['data']);
        new Chart(document.getElementById('batukPieChart'), {
            type: 'pie',
            data: {
                labels: batukLabels,
                datasets: [{
                    data: batukData,
                    backgroundColor: ['#e11d48', '#fbbf24', '#64748b']
                }]
            },
            options: { responsive: true }
        });

        const cancerLabels = @json($cancerPie['labels']);
        const cancerData = @json($cancerPie['data']);
        new Chart(document.getElementById('cancerPieChart'), {
            type: 'doughnut',
            data: {
                labels: cancerLabels,
                datasets: [{
                    data: cancerData,
                    backgroundColor: ['#2563eb', '#059669']
                }]
            },
            options: { responsive: true }
        });

        new Chart(document.getElementById('perokokBarChart'), {
            type: 'bar',
            data: {
                labels: ['Perokok', 'Non-perokok'],
                datasets: [{
                    label: 'Jumlah',
                    data: [@json($perokok), @json($nonPerokok)],
                    backgroundColor: ['#ea580c', '#64748b']
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById('hepatitisBarChart'), {
            type: 'bar',
            data: {
                labels: @json($hepatitisBar['labels']),
                datasets: [{
                    label: 'Jumlah',
                    data: @json($hepatitisBar['data']),
                    backgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endpush
