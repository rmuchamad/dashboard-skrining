@extends('layouts.app')

@section('content')
    <h2 class="text-4xl font-bold tracking-tight text-slate-900">Cek Kesehatan Gratis</h2>
    <p class="mt-1 text-sm text-slate-500">Cek Kesehatan Gratis &gt; Pelayanan</p>

    <div class="mt-4 rounded-md border border-amber-100 bg-amber-50 px-4 py-2 text-sm text-amber-800">
        Anda sedang melakukan pelayanan. Atur pelayanan dan pantau status pemeriksaan peserta di halaman ini.
    </div>

    <div class="mt-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-5 flex items-start gap-3">
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-emerald-100 text-lg text-emerald-600">🩺</div>
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Pelayanan</h3>
                <p class="text-sm text-slate-600">Catat pelayanan peserta hari ini dan tinjau riwayat pelayanan yang belum selesai.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('pelayanan.index') }}">
            <div class="grid gap-2 md:grid-cols-[190px_190px_170px_minmax(220px,1fr)_auto_auto]">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
                <select name="filter_type" class="h-10 rounded-md border border-slate-300 px-3 text-sm">
                    <option value="ticket" @selected(!request('nik') && !request('name'))>Nomor tiket</option>
                    <option value="nik" @selected((bool) request('nik'))>NIK</option>
                    <option value="name" @selected((bool) request('name'))>Nama</option>
                </select>
                <input type="text" name="ticket_number" value="{{ request('ticket_number') ?: request('nik') ?: request('name') }}" class="h-10 rounded-md border border-slate-300 px-3 text-sm" placeholder="Masukkan pencarian">
                <button class="h-10 rounded-md bg-[#00A99D] px-4 text-sm font-semibold text-white hover:bg-[#008f84]">Cari</button>
                <a href="{{ route('pelayanan.index') }}" class="grid h-10 place-items-center rounded-md border border-slate-300 px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
            </div>
        </form>

        <div class="mt-4 flex flex-wrap gap-5 border-b border-slate-200 text-sm font-semibold">
            <a href="{{ route('pelayanan.index', array_merge(request()->query(), ['status' => 'belum_diperiksa'])) }}" class="pb-2 {{ $status === 'belum_diperiksa' ? 'border-b-2 border-[#00A99D] text-[#00A99D]' : 'text-slate-600 hover:text-slate-800' }}">
                Belum Pemeriksaan
            </a>
            <a href="{{ route('pelayanan.index', array_merge(request()->query(), ['status' => 'sedang_diperiksa'])) }}" class="pb-2 {{ $status === 'sedang_diperiksa' ? 'border-b-2 border-[#00A99D] text-[#00A99D]' : 'text-slate-600 hover:text-slate-800' }}">
                Sedang Pemeriksaan
            </a>
            <a href="{{ route('pelayanan.index', array_merge(request()->query(), ['status' => 'selesai_pemeriksaan'])) }}" class="pb-2 {{ $status === 'selesai_pemeriksaan' ? 'border-b-2 border-[#00A99D] text-[#00A99D]' : 'text-slate-600 hover:text-slate-800' }}">
                Selesai Pemeriksaan
            </a>
        </div>

        <div class="mt-3 overflow-hidden rounded-lg border border-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                <thead>
                <tr>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">No</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Nama</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Tanggal Lahir</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Nomor Tiket</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Nama Wali</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Unit Pelayanan</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Pemeriksaan Mandiri</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Pelayanan</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Pemeriksaan</th>
                    <th class="bg-slate-100 px-3 py-2.5 text-left font-semibold text-slate-700">Rapor</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sessions as $session)
                    <tr class="border-t border-slate-100 hover:bg-slate-50">
                        <td class="px-3 py-2.5">{{ $sessions->firstItem() + $loop->index }}</td>
                        <td class="px-3 py-2.5 font-medium text-slate-800">{{ $session->respondent?->name }}</td>
                        <td class="px-3 py-2.5">{{ optional($session->respondent?->birth_date)->format('d M Y') }}</td>
                        <td class="px-3 py-2.5"><span class="rounded-full bg-slate-800 px-2.5 py-1 text-[11px] font-semibold text-white">{{ $session->ticket_number ?? '-' }}</span></td>
                        <td class="px-3 py-2.5">{{ $session->respondent?->guardian_name ?: '-' }}</td>
                        <td class="px-3 py-2.5">{{ $session->respondent?->clinic_name ?? '-' }}</td>
                        <td class="px-3 py-2.5">
                            @php
                                $mandiriComplete = $totalQuestions > 0 && $session->screening_answers_count >= $totalQuestions;
                            @endphp
                            @if($mandiriComplete)
                                <span class="inline-flex whitespace-nowrap rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">Lengkap</span>
                            @else
                                <span class="inline-flex whitespace-nowrap rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-700">Belum Lengkap</span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5">
                            @if($session->service_status === 'belum_diperiksa')
                                <span class="inline-flex whitespace-nowrap rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-700">Belum Pemeriksaan</span>
                            @elseif($session->service_status === 'sedang_diperiksa')
                                <span class="inline-flex whitespace-nowrap rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">Sedang Pemeriksaan</span>
                            @else
                                <span class="inline-flex whitespace-nowrap rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">Selesai Pemeriksaan</span>
                            @endif
                        </td>
                        <td class="px-3 py-2.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('pelayanan.detail', $session) }}" class="inline-flex h-8 items-center rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50">Mulai</a>
                            </div>
                        </td>
                        <td class="px-3 py-2.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <a
                                    target="_blank"
                                    href="{{ $session->report_sent_at ? route('pelayanan.report', $session) : '#' }}"
                                    @if(!$session->report_sent_at) aria-disabled="true" @endif
                                    class="inline-flex h-8 items-center rounded-md border px-3 text-xs font-semibold {{ $session->report_sent_at ? 'border-slate-400 text-slate-800 hover:bg-slate-50' : 'cursor-not-allowed border-slate-200 text-slate-400 bg-slate-100' }}"
                                >
                                    Lihat
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="py-10 text-center text-sm text-slate-500">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-t bg-white p-4">
            <div class="text-sm text-slate-600">
                Menampilkan {{ $sessions->firstItem() ?? 0 }}-{{ $sessions->lastItem() ?? 0 }} dari {{ $sessions->total() }} data
            </div>
            <div>{{ $sessions->onEachSide(1)->links('pagination::tailwind-numeric') }}</div>
        </div>
    </div>
    </div>
@endsection
