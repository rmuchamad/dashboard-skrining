@extends('layouts.app')

@section('content')
    <div class="mb-4 rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('pelayanan.index') }}" class="text-2xl leading-none text-slate-500 hover:text-slate-700">‹</a>
                <h4 class="text-2xl font-bold text-slate-900">Cek Kesehatan Gratis</h4>
                <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $session->service_status === 'belum_diperiksa' ? 'bg-rose-100 text-rose-700' : ($session->service_status === 'sedang_diperiksa' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                    {{ $session->service_status === 'belum_diperiksa' ? 'Belum Pemeriksaan' : ($session->service_status === 'sedang_diperiksa' ? 'Sedang Pemeriksaan' : 'Selesai Pemeriksaan') }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex h-9 items-center rounded-md border border-slate-300 bg-white px-3 text-xs font-semibold text-slate-600">
                    Tanggal Pemeriksaan: {{ optional($session->screened_at)->format('d M Y') }}
                </span>

                @if($session->service_status === 'belum_diperiksa')
                    <form method="POST" action="{{ route('pelayanan.start', $session) }}">
                        @csrf
                        <button class="h-9 rounded-md bg-[#00A99D] px-4 text-xs font-semibold text-white hover:bg-[#008f84]">Mulai Pemeriksaan</button>
                    </form>
                @elseif($session->service_status === 'sedang_diperiksa')
                    <form method="POST" action="{{ route('pelayanan.finish', $session) }}">
                        @csrf
                        <button class="h-9 rounded-md bg-emerald-600 px-4 text-xs font-semibold text-white hover:bg-emerald-700">Selesaikan Layanan</button>
                    </form>
                @else
                    <button class="h-9 cursor-not-allowed rounded-md bg-slate-200 px-4 text-xs font-semibold text-slate-500" type="button" disabled>Selesai Pemeriksaan</button>
                @endif

            </div>
        </div>
        <div class="px-4 py-2 text-xs text-amber-900 bg-amber-50 border-t border-amber-100">
            Anda sedang melakukan pelayanan sebagai faskes. Pastikan data pemeriksaan mandiri dan pemeriksaan nakes terisi.
        </div>
    </div>

    @php($notes = $session->service_notes ?? [])
    @php($giziSections = ['antropometri', 'gds', 'td'])
    @php($gigiSections = ['karies_gigi_hilang', 'penyakit_periodontal'])
    @php($tbSections = ['faktor_xray', 'pemeriksaan_tb'])
    @php($tropisSections = ['frambusia', 'kusta', 'skabies'])
    @php($ppokSections = ['puma'])
    @php($kadarCoSections = ['pernapasan'])
    @php($labSections = ['poct_lipid', 'fibrosis_hati', 'hepatitis', 'fungsi_ginjal_lk', 'kerusakan_ginjal'])
    @php($jantungSections = ['hasil_ekg'])
    @php($kankerUsusSections = ['lanjutan'])
    @php($kankerParuSections = ['usia_45'])
    @php($catinLakiSections = ['hiv', 'sifilis'])
    @php($kankerPayudaraSections = ['sadanis'])
    @php($kankerLeherRahimSections = ['hpv_dna', 'inspekulo_iva'])
    @php($catinPerempuanSections = ['cp_perempuan', 'hiv', 'sifilis'])
    @php($completedItems = 0)
    @foreach($services as $sKey => $sLabel)
        @if($sKey === 'skrining_gizi')
            @foreach($giziSections as $gz)
                @php($completedItems += (($notes['skrining_gizi']['sections'][$gz]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'skrining_gigi')
            @foreach($gigiSections as $gg)
                @php($completedItems += (($notes['skrining_gigi']['sections'][$gg]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'tuberkulosis')
            @foreach($tbSections as $tb)
                @php($completedItems += (($notes['tuberkulosis']['sections'][$tb]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'penyakit_tropis')
            @foreach($tropisSections as $tr)
                @php($completedItems += (($notes['penyakit_tropis']['sections'][$tr]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'ppok')
            @foreach($ppokSections as $pk)
                @php($completedItems += ((($notes['ppok']['sections'] ?? [])[$pk]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'kadar_co')
            @foreach($kadarCoSections as $kc)
                @php($completedItems += ((($notes['kadar_co']['sections'] ?? [])[$kc]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'laboratorium')
            @foreach($labSections as $lb)
                @php($completedItems += (((($notes['laboratorium'] ?? [])['sections'] ?? [])[$lb]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'jantung')
            @foreach($jantungSections as $jt)
                @php($completedItems += (((($notes['jantung'] ?? [])['sections'] ?? [])[$jt]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'kanker_usus')
            @foreach($kankerUsusSections as $kus)
                @php($completedItems += (((($notes['kanker_usus'] ?? [])['sections'] ?? [])[$kus]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'kanker_paru')
            @foreach($kankerParuSections as $kpar)
                @php($completedItems += (((($notes['kanker_paru'] ?? [])['sections'] ?? [])[$kpar]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'catin_laki')
            @foreach($catinLakiSections as $cl)
                @php($completedItems += (((($notes['catin_laki'] ?? [])['sections'] ?? [])[$cl]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'kanker_payudara')
            @foreach($kankerPayudaraSections as $kpd)
                @php($completedItems += (((($notes['kanker_payudara'] ?? [])['sections'] ?? [])[$kpd]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'kanker_leher_rahim')
            @foreach($kankerLeherRahimSections as $klr)
                @php($completedItems += (((($notes['kanker_leher_rahim'] ?? [])['sections'] ?? [])[$klr]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @elseif($sKey === 'catin_perempuan')
            @foreach($catinPerempuanSections as $cp)
                @php($completedItems += (((($notes['catin_perempuan'] ?? [])['sections'] ?? [])[$cp]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
            @endforeach
        @else
            @php($completedItems += (($notes[$sKey]['status'] ?? 'belum') === 'selesai') ? 1 : 0)
        @endif
    @endforeach
    @php($nestedParentsOffset = (($session->respondent?->gender ?? '') === 'male') ? 11 : 13)
    @php($catinLakiSlots = (($session->respondent?->gender ?? '') === 'male') ? count($catinLakiSections) : 0)
    @php($kankerPayudaraSlots = (($session->respondent?->gender ?? '') === 'female') ? count($kankerPayudaraSections) : 0)
    @php($kankerLeherRahimSlots = (($session->respondent?->gender ?? '') === 'female') ? count($kankerLeherRahimSections) : 0)
    @php($catinPerempuanSlots = (($session->respondent?->gender ?? '') === 'female') ? count($catinPerempuanSections) : 0)
    @php($totalItems = (count($services) - $nestedParentsOffset) + count($giziSections) + count($gigiSections) + count($tbSections) + count($tropisSections) + count($ppokSections) + count($kadarCoSections) + count($labSections) + count($jantungSections) + count($kankerUsusSections) + count($kankerParuSections) + $catinLakiSlots + $kankerPayudaraSlots + $kankerLeherRahimSlots + $catinPerempuanSlots)
    @php($mandiriDone = collect($mandiriRows)->where('complete', true)->count())

    <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                    {{ strtoupper(substr((string) ($session->respondent?->name ?? 'P'), 0, 1)) }}
                </div>
                <div>
                    <p class="text-base font-bold text-slate-900">{{ $session->respondent?->name }}</p>
                    <p class="text-xs text-slate-500">{{ $session->respondent?->gender === 'male' ? 'Laki-laki' : 'Perempuan' }} • {{ optional($session->respondent?->birth_date)->format('d M Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                    {{ $session->service_status === 'belum_diperiksa' ? 'Belum Pemeriksaan' : ($session->service_status === 'sedang_diperiksa' ? 'Sedang Pemeriksaan' : 'Selesai Pemeriksaan') }}
                </span>
                <button id="btnOpenDetailData" class="h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50" type="button">Detail Data</button>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-2 gap-3 text-sm md:grid-cols-5">
            <div><p class="text-xs text-slate-500">NIK</p><p class="font-semibold text-slate-800">{{ $session->respondent?->nik }}</p></div>
            <div><p class="text-xs text-slate-500">Nama Ibu/Wali</p><p class="font-semibold text-slate-800">{{ $session->respondent?->guardian_name ?: '-' }}</p></div>
            <div><p class="text-xs text-slate-500">Umur</p><p class="font-semibold text-slate-800">{{ $session->respondent?->age ? $session->respondent?->age.' tahun' : '-' }}</p></div>
            <div><p class="text-xs text-slate-500">Nomor Tiket</p><p class="font-semibold text-slate-800">{{ $session->ticket_number }}</p></div>
            <div><p class="text-xs text-slate-500">Unit</p><p class="font-semibold text-slate-800">{{ $session->respondent?->clinic_name ?? '-' }}</p></div>
        </div>
    </div>

    <div class="mb-4 rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-4 py-3">
            <h5 class="text-xl font-bold text-slate-900">Pemeriksaan Mandiri</h5>
            <p class="text-xs text-slate-500">Jumlah Pemeriksaan ({{ $mandiriDone }}/{{ count($mandiriRows) }})</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-left text-xs font-semibold text-slate-700">
                <tr>
                    <th class="px-4 py-2.5">Layanan</th>
                    <th class="px-4 py-2.5">Status</th>
                    <th class="px-4 py-2.5">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($mandiriRows as $row)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-2.5 text-slate-800">{{ $row['title'] }}</td>
                        <td class="px-4 py-2.5">
                            @if($row['complete'])
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Lengkap</span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-600">Belum Lengkap</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5">
                            <a href="{{ route('screening.wizard', ['kategori' => $row['slug']]) }}" class="inline-flex h-7 items-center rounded-md border border-[#00A99D] px-2.5 text-xs font-semibold text-[#00A99D] hover:bg-teal-50">Input Data</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form class="rounded-xl border border-slate-200 bg-white shadow-sm" method="POST" action="{{ route('pelayanan.detail.save', $session) }}">
        @csrf
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-4 py-3">
            <div>
                <h5 class="text-xl font-bold text-slate-900">Pelayanan oleh Nakes</h5>
                <p class="text-xs text-slate-500">Progress layanan ({{ $completedItems }}/{{ $totalItems }})</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="h-8 rounded-md bg-slate-200 px-3 text-xs font-semibold text-slate-500" type="button" disabled>Kirim Rapor</button>
                <a href="{{ route('pelayanan.report', $session) }}" class="inline-flex h-8 items-center rounded-md border border-slate-300 px-3 text-xs font-semibold text-slate-500">Lihat Rapor</a>
            </div>
        </div>
        <div id="nakesNotice" class="mx-4 mt-3 hidden rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700">
            Pilih <strong>Ya</strong> pada kolom diperiksa terlebih dahulu untuk mengaktifkan aksi Input Data.
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100 text-left text-xs font-semibold text-slate-700">
                <tr>
                    <th class="px-4 py-2.5">Layanan</th>
                    <th class="px-4 py-2.5">Diperiksa</th>
                    <th class="px-4 py-2.5">Status</th>
                    <th class="px-4 py-2.5">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($services as $key => $label)
                    @if($key === 'skrining_gizi')
                        @php($genderLabel = $session->respondent?->gender === 'male' ? 'Laki-laki' : 'Perempuan')
                        @php($giziSections = [
                            'antropometri' => 'Gizi (BB - TB - Lingkar Perut) '.$genderLabel,
                            'gds' => 'Pemeriksaan Gula Darah Dewasa Lansia',
                            'td' => 'Tekanan Darah Dewasa Lansia',
                        ])
                        @php($giziOpen = old('gizi_open', '1') === '1')
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="gizi-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $giziOpen ? 'true' : 'false' }}">
                                    <span id="giziCaret" class="text-slate-600 transition {{ $giziOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>Skrining Gizi, Tekanan Darah, dan Gula Darah {{ $genderLabel }}</span>
                                </button>
                                <input type="hidden" id="gizi_open" name="gizi_open" value="{{ $giziOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($giziSections as $sKey => $sLabel)
                            @php($rowId = 'skrining_gizi__'.$sKey)
                            @php($item = $notes['skrining_gizi']['sections'][$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 gizi-child-row {{ $giziOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[skrining_gizi][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[skrining_gizi][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $rowStatus === 'selesai' ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'skrining_gizi', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'telinga_mata')
                        @php($tmOpen = old('tm_open', '1') === '1')
                        @php($item = $notes[$key] ?? [])
                        @php($rowStatus = $item['status'] ?? 'belum')
                        @php($isChecked = (bool)($item['enabled'] ?? true))
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="tm-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $tmOpen ? 'true' : 'false' }}">
                                    <span id="tmCaret" class="text-slate-600 transition {{ $tmOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $label }}</span>
                                </button>
                                <input type="hidden" id="tm_open" name="tm_open" value="{{ $tmOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        <tr class="border-t border-slate-100 tm-child-row {{ $tmOpen ? '' : 'hidden' }}">
                            <td class="px-4 py-2.5 text-slate-800">{{ $label }}</td>
                            <td class="px-4 py-2.5">
                                <label class="inline-flex cursor-pointer items-center gap-2">
                                    <span class="text-xs text-slate-600">Tidak</span>
                                    <input
                                        type="checkbox"
                                        class="nakes-toggle peer sr-only"
                                        data-key="{{ $key }}"
                                        @checked($isChecked)
                                        @disabled($session->service_status === 'selesai_pemeriksaan')
                                    >
                                    <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                    <span class="text-xs font-semibold text-slate-700">Ya</span>
                                </label>
                                <input type="hidden" id="status_{{ $key }}" name="notes[{{ $key }}][status]" value="{{ $rowStatus }}">
                                <input type="hidden" id="enabled_{{ $key }}" name="notes[{{ $key }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                            </td>
                            <td class="px-4 py-2.5">
                                <span id="badge_{{ $key }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $rowStatus === 'selesai' ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                        data-key="{{ $key }}"
                                        data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => $key]) }}"
                                        data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                        @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                    >
                                        Input Data
                                    </button>
                                    <span id="catatan_preview_{{ $key }}" class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                </div>
                                <input type="hidden" id="catatan_{{ $key }}" name="notes[{{ $key }}][catatan]" value="{{ $item['catatan'] ?? '' }}">
                                <input type="hidden" id="details_{{ $key }}" name="notes[{{ $key }}][details_json]" value="{{ isset($item['details']) ? e(json_encode($item['details'])) : '' }}">
                            </td>
                        </tr>
                        @continue
                    @endif
                    @if($key === 'skrining_gigi')
                        @php($gigiOpen = old('gigi_open', '1') === '1')
                        @php($gigiLabel = 'Skrining Gigi - Dewasa >25 tahun')
                        @php($gigiMap = [
                            'karies_gigi_hilang' => 'Skrining Karies dan Gigi Hilang',
                            'penyakit_periodontal' => 'Skrining Penyakit Periodontal',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="gigi-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $gigiOpen ? 'true' : 'false' }}">
                                    <span id="gigiCaret" class="text-slate-600 transition {{ $gigiOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $gigiLabel }}</span>
                                </button>
                                <input type="hidden" id="gigi_open" name="gigi_open" value="{{ $gigiOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($gigiMap as $sKey => $sLabel)
                            @php($rowId = 'skrining_gigi__'.$sKey)
                            @php($item = $notes['skrining_gigi']['sections'][$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 gigi-child-row {{ $gigiOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[skrining_gigi][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[skrining_gigi][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $rowStatus === 'selesai' ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'skrining_gigi', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'tuberkulosis')
                        @php($tbOpen = old('tb_open', '1') === '1')
                        @php($tbParentTitle = 'Tuberkulosis - Skrining Nakes Dewasa & Lansia')
                        @php($tbMap = [
                            'faktor_xray' => 'Faktor Risiko dan Skrining X-Ray TB (Dewasa & Lansia)',
                            'pemeriksaan_tb' => 'Pemeriksaan Tuberkulosis (Dewasa & Lansia)',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="tb-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $tbOpen ? 'true' : 'false' }}">
                                    <span id="tbCaret" class="text-slate-600 transition {{ $tbOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $tbParentTitle }}</span>
                                </button>
                                <input type="hidden" id="tb_open" name="tb_open" value="{{ $tbOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($tbMap as $sKey => $sLabel)
                            @php($rowId = 'tuberkulosis__'.$sKey)
                            @php($item = $notes['tuberkulosis']['sections'][$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 tb-child-row {{ $tbOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[tuberkulosis][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[tuberkulosis][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $rowStatus === 'selesai' ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'tuberkulosis', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'penyakit_tropis')
                        @php($tropisOpen = old('tropis_open', '1') === '1')
                        @php($tropisParentTitle = 'Layanan Penyakit Tropis Terabaikan')
                        @php($tropisMap = [
                            'frambusia' => 'Pemeriksaan Penyakit Frambusia (untuk daerah endemis atau berisiko frambusia)',
                            'kusta' => 'Pemeriksaan Penyakit Kusta',
                            'skabies' => 'Pemeriksaan Penyakit Skabies',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="tropis-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $tropisOpen ? 'true' : 'false' }}">
                                    <span id="tropisCaret" class="text-slate-600 transition {{ $tropisOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $tropisParentTitle }}</span>
                                </button>
                                <input type="hidden" id="tropis_open" name="tropis_open" value="{{ $tropisOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($tropisMap as $sKey => $sLabel)
                            @php($rowId = 'penyakit_tropis__'.$sKey)
                            @php($item = $notes['penyakit_tropis']['sections'][$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 tropis-child-row {{ $tropisOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[penyakit_tropis][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[penyakit_tropis][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $rowStatus === 'selesai' ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'penyakit_tropis', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'ppok')
                        @php($ppokOpen = old('ppok_open', '1') === '1')
                        @php($ppokParentTitle = 'Pemeriksaan PPOK (Skrining PUMA)')
                        @php($ppokMap = [
                            'puma' => 'Pemeriksaan PPOK (Skrining PUMA)',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="ppok-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $ppokOpen ? 'true' : 'false' }}">
                                    <span id="ppokCaret" class="text-slate-600 transition {{ $ppokOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $ppokParentTitle }}</span>
                                </button>
                                <input type="hidden" id="ppok_open" name="ppok_open" value="{{ $ppokOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($ppokMap as $sKey => $sLabel)
                            @php($rowId = 'ppok__'.$sKey)
                            @php($item = ($notes['ppok']['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 ppok-child-row {{ $ppokOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[ppok][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[ppok][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $rowStatus === 'selesai' ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'ppok', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'kadar_co')
                        @php($kadarOpen = old('kadar_open', '1') === '1')
                        @php($kadarParentTitle = 'Pemeriksaan Kadar CO (Tatalaksana Merokok)')
                        @php($kadarMap = [
                            'pernapasan' => 'Pemeriksaan Kadar CO (Hanya Diisi Apabila Merokok atau Terpapar Asap Rokok)',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="kadar-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $kadarOpen ? 'true' : 'false' }}">
                                    <span id="kadarCaret" class="text-slate-600 transition {{ $kadarOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $kadarParentTitle }}</span>
                                </button>
                                <input type="hidden" id="kadar_open" name="kadar_open" value="{{ $kadarOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($kadarMap as $sKey => $sLabel)
                            @php($rowId = 'kadar_co__'.$sKey)
                            @php($item = (($notes['kadar_co'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 kadar-child-row {{ $kadarOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[kadar_co][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[kadar_co][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($kadarBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($kadarBadgeText = $rowStatus === 'selesai' ? 'Sudah diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $kadarBadgeClass }}">
                                        {{ $kadarBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'kadar_co', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'laboratorium')
                        @php($labOpen = old('lab_open', '1') === '1')
                        @php($labGenderLabel = $session->respondent?->gender === 'male' ? 'Laki-laki' : 'Perempuan')
                        @php($labParentTitle = 'Skrining Laboratorium = > 40 thn '.$labGenderLabel.' Gula Darah, Fungsi Ginjal, Hati, Profil Lipid')
                        @php($labMap = [
                            'poct_lipid' => 'POCT Lipid Panel (Khusus usia >= 40 thn dan penyandang HT dan/atau DM)',
                            'fibrosis_hati' => 'Pemeriksaan Fibrosis/Sirosis Hati',
                            'hepatitis' => 'Pemeriksaan Hepatitis',
                            'fungsi_ginjal_lk' => 'Skrining Fungsi Ginjal '.$labGenderLabel.' (hanya untuk = > 40 tahun dengan risiko HT DM)',
                            'kerusakan_ginjal' => 'Skrining Kerusakan Ginjal (hanya untuk = > 40 tahun dengan risiko HT DM)',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="lab-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $labOpen ? 'true' : 'false' }}">
                                    <span id="labCaret" class="text-slate-600 transition {{ $labOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $labParentTitle }}</span>
                                </button>
                                <input type="hidden" id="lab_open" name="lab_open" value="{{ $labOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($labMap as $sKey => $sLabel)
                            @php($rowId = 'laboratorium__'.$sKey)
                            @php($item = (($notes['laboratorium'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 lab-child-row {{ $labOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[laboratorium][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[laboratorium][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($labBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($labBadgeText = $rowStatus === 'selesai' ? 'Selesai diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $labBadgeClass }}">
                                        {{ $labBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'laboratorium', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'jantung')
                        @php($jantungOpen = old('jantung_open', '1') === '1')
                        @php($jantungParentTitle = 'Skrining Jantung (Pemeriksaan EKG - hanya penyandang HT & DM)')
                        @php($jantungMap = [
                            'hasil_ekg' => 'Hasil Pemeriksaan - Skrining Jantung',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="jantung-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $jantungOpen ? 'true' : 'false' }}">
                                    <span id="jantungCaret" class="text-slate-600 transition {{ $jantungOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $jantungParentTitle }}</span>
                                </button>
                                <input type="hidden" id="jantung_open" name="jantung_open" value="{{ $jantungOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($jantungMap as $sKey => $sLabel)
                            @php($rowId = 'jantung__'.$sKey)
                            @php($item = (($notes['jantung'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 jantung-child-row {{ $jantungOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[jantung][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[jantung][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($jtgBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($jtgBadgeText = $rowStatus === 'selesai' ? 'Sudah diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $jtgBadgeClass }}">
                                        {{ $jtgBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'jantung', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'kanker_usus')
                        @php($kusOpen = old('kus_open', '1') === '1')
                        @php($kusParentTitle = 'Skrining Kanker Usus (TL APCS)')
                        @php($kusMap = [
                            'lanjutan' => 'Pemeriksaan Lanjutan Kanker Usus',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="kus-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $kusOpen ? 'true' : 'false' }}">
                                    <span id="kusCaret" class="text-slate-600 transition {{ $kusOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $kusParentTitle }}</span>
                                </button>
                                <input type="hidden" id="kus_open" name="kus_open" value="{{ $kusOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($kusMap as $sKey => $sLabel)
                            @php($rowId = 'kanker_usus__'.$sKey)
                            @php($item = (($notes['kanker_usus'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 kus-child-row {{ $kusOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[kanker_usus][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[kanker_usus][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($kusBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($kusBadgeText = $rowStatus === 'selesai' ? 'Sudah diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $kusBadgeClass }}">
                                        {{ $kusBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'kanker_usus', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'kanker_paru')
                        @php($kparuOpen = old('kparu_open', '1') === '1')
                        @php($kparuParentTitle = 'Skrining Kanker Paru (Usia = > 45 tahun)')
                        @php($kparuMap = [
                            'usia_45' => 'Skrining Kanker Paru (Usia = > 45 thn)',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="kparu-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $kparuOpen ? 'true' : 'false' }}">
                                    <span id="kparuCaret" class="text-slate-600 transition {{ $kparuOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $kparuParentTitle }}</span>
                                </button>
                                <input type="hidden" id="kparu_open" name="kparu_open" value="{{ $kparuOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($kparuMap as $sKey => $sLabel)
                            @php($rowId = 'kanker_paru__'.$sKey)
                            @php($item = (($notes['kanker_paru'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 kparu-child-row {{ $kparuOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[kanker_paru][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[kanker_paru][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($kparuBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($kparuBadgeText = $rowStatus === 'selesai' ? 'Selesai diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $kparuBadgeClass }}">
                                        {{ $kparuBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'kanker_paru', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'catin_laki')
                        @php($catinOpen = old('catin_open', '1') === '1')
                        @php($catinParentTitle = 'Pemeriksaan Calon Pengantin Laki-laki')
                        @php($catinMap = [
                            'hiv' => 'Pemeriksaan HIV',
                            'sifilis' => 'Pemeriksaan Sifilis',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="catin-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $catinOpen ? 'true' : 'false' }}">
                                    <span id="catinCaret" class="text-slate-600 transition {{ $catinOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $catinParentTitle }}</span>
                                </button>
                                <input type="hidden" id="catin_open" name="catin_open" value="{{ $catinOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($catinMap as $sKey => $sLabel)
                            @php($rowId = 'catin_laki__'.$sKey)
                            @php($item = (($notes['catin_laki'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 catin-child-row {{ $catinOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[catin_laki][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[catin_laki][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($catinBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($catinBadgeText = $rowStatus === 'selesai' ? 'Sudah diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $catinBadgeClass }}">
                                        {{ $catinBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'catin_laki', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'kanker_payudara')
                        @php($kpdOpen = old('kpd_open', '1') === '1')
                        @php($kpdParentTitle = 'Skrining Kanker Payudara')
                        @php($kpdMap = [
                            'sadanis' => 'Skrining Kanker Payudara',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="kpd-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $kpdOpen ? 'true' : 'false' }}">
                                    <span id="kpdCaret" class="text-slate-600 transition {{ $kpdOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $kpdParentTitle }}</span>
                                </button>
                                <input type="hidden" id="kpd_open" name="kpd_open" value="{{ $kpdOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($kpdMap as $sKey => $sLabel)
                            @php($rowId = 'kanker_payudara__'.$sKey)
                            @php($item = (($notes['kanker_payudara'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 kpd-child-row {{ $kpdOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[kanker_payudara][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[kanker_payudara][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($kpdBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($kpdBadgeText = $rowStatus === 'selesai' ? 'Selesai diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $kpdBadgeClass }}">
                                        {{ $kpdBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'kanker_payudara', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'kanker_leher_rahim')
                        @php($klrOpen = old('klr_open', '1') === '1')
                        @php($klrParentTitle = 'Skrining Kanker Leher Rahim')
                        @php($klrMap = [
                            'hpv_dna' => 'Hasil Pemeriksaan HPV-DNA',
                            'inspekulo_iva' => 'Pemeriksaan Inspekulo dan IVA',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="klr-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $klrOpen ? 'true' : 'false' }}">
                                    <span id="klrCaret" class="text-slate-600 transition {{ $klrOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $klrParentTitle }}</span>
                                </button>
                                <input type="hidden" id="klr_open" name="klr_open" value="{{ $klrOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($klrMap as $sKey => $sLabel)
                            @php($rowId = 'kanker_leher_rahim__'.$sKey)
                            @php($item = (($notes['kanker_leher_rahim'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 klr-child-row {{ $klrOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[kanker_leher_rahim][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[kanker_leher_rahim][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($klrBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($klrBadgeText = $rowStatus === 'selesai' ? 'Selesai diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $klrBadgeClass }}">
                                        {{ $klrBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'kanker_leher_rahim', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @if($key === 'catin_perempuan')
                        @php($cpOpen = old('cp_open', '1') === '1')
                        @php($cpParentTitle = 'Pemeriksaan Calon Pengantin Perempuan')
                        @php($cpMap = [
                            'cp_perempuan' => 'Pemeriksaan Calon Pengantin Perempuan',
                            'hiv' => 'Pemeriksaan HIV',
                            'sifilis' => 'Pemeriksaan Sifilis',
                        ])
                        <tr class="border-t border-slate-100 bg-slate-50">
                            <td class="px-4 py-2.5 text-slate-800 font-semibold" colspan="4">
                                <button type="button" class="cp-parent-toggle inline-flex items-center gap-2 text-left text-sm" aria-expanded="{{ $cpOpen ? 'true' : 'false' }}">
                                    <span id="cpCaret" class="text-slate-600 transition {{ $cpOpen ? 'rotate-180' : '' }}">⌃</span>
                                    <span>{{ $cpParentTitle }}</span>
                                </button>
                                <input type="hidden" id="cp_open" name="cp_open" value="{{ $cpOpen ? '1' : '0' }}">
                            </td>
                        </tr>
                        @foreach($cpMap as $sKey => $sLabel)
                            @php($rowId = 'catin_perempuan__'.$sKey)
                            @php($item = (($notes['catin_perempuan'] ?? [])['sections'] ?? [])[$sKey] ?? [])
                            @php($rowStatus = $item['status'] ?? 'belum')
                            @php($isChecked = (bool)($item['enabled'] ?? true))
                            <tr class="border-t border-slate-100 cp-child-row {{ $cpOpen ? '' : 'hidden' }}">
                                <td class="px-4 py-2.5 text-slate-800">{{ $sLabel }}</td>
                                <td class="px-4 py-2.5">
                                    <label class="inline-flex cursor-pointer items-center gap-2">
                                        <span class="text-xs text-slate-600">Tidak</span>
                                        <input
                                            type="checkbox"
                                            class="nakes-toggle peer sr-only"
                                            data-key="{{ $rowId }}"
                                            @checked($isChecked)
                                            @disabled($session->service_status === 'selesai_pemeriksaan')
                                        >
                                        <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                        <span class="text-xs font-semibold text-slate-700">Ya</span>
                                    </label>
                                    <input type="hidden" id="status_{{ $rowId }}" name="notes[catin_perempuan][sections][{{ $sKey }}][status]" value="{{ $rowStatus }}">
                                    <input type="hidden" id="enabled_{{ $rowId }}" name="notes[catin_perempuan][sections][{{ $sKey }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                                </td>
                                <td class="px-4 py-2.5">
                                    @php($cpBadgeClass = $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($rowStatus === 'sedang' ? 'border border-amber-300 bg-amber-50 text-amber-800' : 'bg-slate-200 text-slate-600'))
                                    @php($cpBadgeText = $rowStatus === 'selesai' ? 'Sudah diperiksa' : ($rowStatus === 'sedang' ? 'Dalam Pemeriksaan' : 'Belum diperiksa'))
                                    <span id="badge_{{ $rowId }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $cpBadgeClass }}">
                                        {{ $cpBadgeText }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                            data-key="{{ $rowId }}"
                                            data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => 'catin_perempuan', 'section' => $sKey]) }}"
                                            data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                            @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                        >
                                            Input Data
                                        </button>
                                        <span class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @continue
                    @endif
                    @php($item = $notes[$key] ?? [])
                    @php($rowStatus = $item['status'] ?? 'belum')
                    @php($isChecked = (bool)($item['enabled'] ?? true))
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-2.5 text-slate-800">{{ $label }}</td>
                        <td class="px-4 py-2.5">
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <span class="text-xs text-slate-600">Tidak</span>
                                <input
                                    type="checkbox"
                                    class="nakes-toggle peer sr-only"
                                    data-key="{{ $key }}"
                                    @checked($isChecked)
                                    @disabled($session->service_status === 'selesai_pemeriksaan')
                                >
                                <span class="relative h-5 w-10 rounded-full bg-slate-300 transition peer-checked:bg-[#00A99D] peer-disabled:opacity-50 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all peer-checked:after:left-5"></span>
                                <span class="text-xs font-semibold text-slate-700">Ya</span>
                            </label>
                            <input type="hidden" id="status_{{ $key }}" name="notes[{{ $key }}][status]" value="{{ $rowStatus }}">
                            <input type="hidden" id="enabled_{{ $key }}" name="notes[{{ $key }}][enabled]" value="{{ $isChecked ? '1' : '0' }}">
                        </td>
                        <td class="px-4 py-2.5">
                            <span id="badge_{{ $key }}" class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $rowStatus === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                {{ $rowStatus === 'selesai' ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="nakes-input-btn h-8 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400"
                                    data-key="{{ $key }}"
                                    data-url="{{ route('pelayanan.nakes.input', ['session' => $session, 'serviceKey' => $key]) }}"
                                    data-locked="{{ $session->service_status === 'selesai_pemeriksaan' ? '1' : '0' }}"
                                    @disabled(!$isChecked || $session->service_status === 'selesai_pemeriksaan')
                                >
                                    Input Data
                                </button>
                                <span id="catatan_preview_{{ $key }}" class="max-w-[260px] truncate text-xs text-slate-500">{{ $item['catatan'] ?? '' }}</span>
                            </div>
                            <input type="hidden" id="catatan_{{ $key }}" name="notes[{{ $key }}][catatan]" value="{{ $item['catatan'] ?? '' }}">
                            <input type="hidden" id="details_{{ $key }}" name="notes[{{ $key }}][details_json]" value="{{ isset($item['details']) ? e(json_encode($item['details'])) : '' }}">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-end border-t border-slate-200 bg-slate-50 px-4 py-3">
            <button class="h-10 rounded-md bg-[#00A99D] px-4 text-sm font-semibold text-white hover:bg-[#008f84] disabled:cursor-not-allowed disabled:opacity-50" type="submit" @disabled($session->service_status === 'selesai_pemeriksaan')>Simpan Perubahan</button>
        </div>
    </form>

    {{-- Modal Detail Data --}}
    <div id="detailDataModal" class="fixed inset-0 z-[220] hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div id="detailDataBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
        <div class="relative w-full max-w-2xl rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-2xl">
            <div class="mb-3 flex items-center justify-between">
                <h5 class="text-xl font-bold text-slate-900">Detail Data</h5>
                <button type="button" id="detailDataClose" class="rounded p-1 text-slate-500 hover:bg-slate-100">×</button>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-slate-500">NIK</p>
                    <p class="font-semibold text-slate-800">{{ $session->respondent?->nik }}</p>
                    <p class="mt-2 text-xs text-slate-500">Tanggal Lahir</p>
                    <p class="font-semibold text-slate-800">{{ optional($session->respondent?->birth_date)->format('d M Y') }}</p>
                    <p class="mt-2 text-xs text-slate-500">No. HP/WA Orang Tua</p>
                    <p class="font-semibold text-slate-800">{{ $session->respondent?->guardian_phone ?: '-' }}</p>
                    <p class="mt-2 text-xs text-slate-500">Pekerjaan</p>
                    <p class="font-semibold text-slate-800">{{ $session->respondent?->work_unit ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Nama</p>
                    <p class="font-semibold text-slate-800">{{ $session->respondent?->name }}</p>
                    <p class="mt-2 text-xs text-slate-500">Jenis Kelamin</p>
                    <p class="font-semibold text-slate-800">{{ $session->respondent?->gender === 'male' ? 'Laki-Laki' : 'Perempuan' }}</p>
                </div>
                <div class="col-span-2 border-t pt-3">
                    <p class="text-xs text-slate-500">Alamat Domisili</p>
                    <p class="font-semibold text-slate-800">{{ $session->respondent?->address ?: '-' }}</p>
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        <div><p class="text-xs text-slate-500">Provinsi</p><p class="font-semibold text-slate-800">{{ $session->respondent?->province ?: '-' }}</p></div>
                        <div><p class="text-xs text-slate-500">Kota</p><p class="font-semibold text-slate-800">{{ $session->respondent?->regency ?: '-' }}</p></div>
                        <div><p class="text-xs text-slate-500">Kecamatan</p><p class="font-semibold text-slate-800">{{ $session->respondent?->district ?: '-' }}</p></div>
                        <div><p class="text-xs text-slate-500">Kelurahan</p><p class="font-semibold text-slate-800">{{ $session->respondent?->village ?: '-' }}</p></div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" id="btnOpenEditData" class="h-9 rounded-md border border-[#00A99D] px-3 text-xs font-semibold text-[#00A99D] hover:bg-teal-50">Ubah data</button>
                <button type="button" id="detailDataClose2" class="h-9 rounded-md bg-[#00A99D] px-3 text-xs font-semibold text-white hover:bg-[#008f84]">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Modal Ubah Data --}}
    <div id="editDataModal" class="fixed inset-0 z-[225] hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div id="editDataBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
        <div class="relative w-full max-w-2xl rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-2xl">
            <div class="mb-3 flex items-center justify-between">
                <h5 class="text-xl font-bold text-slate-900">Ubah Data</h5>
                <button type="button" id="editDataClose" class="rounded p-1 text-slate-500 hover:bg-slate-100">×</button>
            </div>
            <form method="POST" action="{{ route('pelayanan.detail.update_data', $session) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700">No. Whatsapp Aktif Individu <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-md border border-slate-300">
                        <span class="grid place-items-center border-r border-slate-200 bg-slate-50 px-3 text-xs text-slate-700">+62</span>
                        <input id="editPhoneLocal" type="text" value="{{ ltrim(preg_replace('/^\+?62/', '', preg_replace('/\D/', '', (string)($session->respondent?->phone ?? ''))), '0') }}" class="h-10 w-full rounded-r-md px-3 text-sm outline-none">
                    </div>
                    <input type="hidden" id="editPhoneHidden" name="phone" value="{{ $session->respondent?->phone }}">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700">Pekerjaan <span class="text-rose-500">*</span></label>
                    <input name="work_unit" value="{{ $session->respondent?->work_unit }}" class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm" required>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700">Alamat Domisili <span class="text-rose-500">*</span></label>
                    <input value="{{ collect([$session->respondent?->province, $session->respondent?->regency, $session->respondent?->district, $session->respondent?->village])->filter()->implode(', ') }}" class="h-10 w-full rounded-md border border-slate-300 bg-slate-50 px-3 text-sm" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700">Detail Alamat Domisili <span class="text-rose-500">*</span></label>
                    <textarea name="address" rows="3" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>{{ $session->respondent?->address }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button class="h-9 rounded-md bg-[#00A99D] px-4 text-xs font-semibold text-white hover:bg-[#008f84]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Diperiksa --}}
    <div id="toggleConfirmModal" class="fixed inset-0 z-[230] hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div id="toggleConfirmBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
        <div class="relative w-full max-w-md rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-2xl">
            <button type="button" id="toggleConfirmClose" class="absolute right-3 top-3 rounded p-1 text-slate-400 hover:bg-slate-100">×</button>
            <div class="mx-auto mb-2 grid h-10 w-10 place-items-center rounded-full bg-amber-100 text-lg font-bold text-amber-600">!</div>
            <h5 id="toggleConfirmTitle" class="text-center text-xl font-bold text-slate-900">Konfirmasi</h5>
            <p id="toggleConfirmText" class="mt-2 text-center text-xs text-slate-600">
                Apakah layanan ini ingin ditandai diperiksa?
            </p>
            <div class="mt-4 grid grid-cols-2 gap-2">
                <button type="button" id="toggleConfirmCancel" class="h-9 rounded-md border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Kembali</button>
                <button type="button" id="toggleConfirmOk" class="h-9 rounded-md bg-[#00A99D] text-xs font-semibold text-white hover:bg-[#008f84]">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    (function () {
        var detailModal = document.getElementById('detailDataModal');
        var editModal = document.getElementById('editDataModal');
        var openDetail = document.getElementById('btnOpenDetailData');
        var openEdit = document.getElementById('btnOpenEditData');
        var closeDetails = [document.getElementById('detailDataClose'), document.getElementById('detailDataClose2'), document.getElementById('detailDataBackdrop')];
        var closeEdits = [document.getElementById('editDataClose'), document.getElementById('editDataBackdrop')];
        var phoneLocal = document.getElementById('editPhoneLocal');
        var phoneHidden = document.getElementById('editPhoneHidden');

        function openModal(modal) {
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
        function closeModal(modal) {
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (!detailModal.classList.contains('flex') && !editModal.classList.contains('flex')) {
                document.body.classList.remove('overflow-hidden');
            }
        }
        function syncPhone() {
            var p = (phoneLocal && phoneLocal.value) ? phoneLocal.value.replace(/\D/g, '') : '';
            if (p.startsWith('0')) p = p.slice(1);
            if (phoneHidden) phoneHidden.value = p ? ('0' + p) : '';
        }

        if (openDetail) openDetail.addEventListener('click', function () { openModal(detailModal); });
        if (openEdit) openEdit.addEventListener('click', function () { closeModal(detailModal); openModal(editModal); });
        closeDetails.forEach(function (el) { if (el) el.addEventListener('click', function () { closeModal(detailModal); }); });
        closeEdits.forEach(function (el) { if (el) el.addEventListener('click', function () { closeModal(editModal); }); });
        if (phoneLocal) phoneLocal.addEventListener('input', syncPhone);
        syncPhone();
    })();

    (function () {
        var notice = document.getElementById('nakesNotice');
        var toggleModal = document.getElementById('toggleConfirmModal');
        var toggleBackdrop = document.getElementById('toggleConfirmBackdrop');
        var toggleClose = document.getElementById('toggleConfirmClose');
        var toggleCancel = document.getElementById('toggleConfirmCancel');
        var toggleOk = document.getElementById('toggleConfirmOk');
        var toggleTitle = document.getElementById('toggleConfirmTitle');
        var toggleText = document.getElementById('toggleConfirmText');
        var pendingToggle = null;
        var pendingNext = false;

        function openToggleModal() {
            if (!toggleModal) return;
            toggleModal.classList.remove('hidden');
            toggleModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
        function closeToggleModal() {
            if (!toggleModal) return;
            toggleModal.classList.add('hidden');
            toggleModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
        function setNotice(show) {
            if (!notice) return;
            notice.classList.toggle('hidden', !show);
        }

        function setRowState(key, checked) {
            var enabledInput = document.getElementById('enabled_' + key);
            var btn = document.querySelector('.nakes-input-btn[data-key="' + key + '"]');
            if (enabledInput) enabledInput.value = checked ? '1' : '0';
            if (btn) {
                var locked = btn.getAttribute('data-locked') === '1';
                btn.disabled = locked || !checked;
            }
        }

        document.querySelectorAll('.nakes-toggle').forEach(function (toggle) {
            var key = toggle.getAttribute('data-key');
            setRowState(key, toggle.checked);
            toggle.addEventListener('change', function () {
                pendingToggle = toggle;
                pendingNext = !!toggle.checked;
                if (toggleTitle) toggleTitle.textContent = pendingNext ? 'Tandai Diperiksa' : 'Tandai Tidak Diperiksa';
                if (toggleText) toggleText.textContent = pendingNext
                    ? 'Layanan akan ditandai diperiksa. Lanjutkan perubahan?'
                    : 'Layanan akan ditandai tidak diperiksa. Lanjutkan perubahan?';
                toggle.checked = !pendingNext;
                openToggleModal();
            });
        });

        document.querySelectorAll('.nakes-input-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (btn.disabled) {
                    setNotice(true);
                    return;
                }
                var url = btn.getAttribute('data-url') || '';
                if (url) window.location.href = url;
                setNotice(false);
            });
        });

        var giziToggle = document.querySelector('.gizi-parent-toggle');
        if (giziToggle) {
            var giziOpenInput = document.getElementById('gizi_open');
            var giziCaret = document.getElementById('giziCaret');
            giziToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.gizi-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.gizi-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (giziOpenInput) giziOpenInput.value = nextOpen ? '1' : '0';
                if (giziCaret) giziCaret.classList.toggle('rotate-180', nextOpen);
                giziToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var tmToggle = document.querySelector('.tm-parent-toggle');
        if (tmToggle) {
            var tmOpenInput = document.getElementById('tm_open');
            var tmCaret = document.getElementById('tmCaret');
            tmToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.tm-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.tm-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (tmOpenInput) tmOpenInput.value = nextOpen ? '1' : '0';
                if (tmCaret) tmCaret.classList.toggle('rotate-180', nextOpen);
                tmToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var gigiToggle = document.querySelector('.gigi-parent-toggle');
        if (gigiToggle) {
            var gigiOpenInput = document.getElementById('gigi_open');
            var gigiCaret = document.getElementById('gigiCaret');
            gigiToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.gigi-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.gigi-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (gigiOpenInput) gigiOpenInput.value = nextOpen ? '1' : '0';
                if (gigiCaret) gigiCaret.classList.toggle('rotate-180', nextOpen);
                gigiToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var tbToggle = document.querySelector('.tb-parent-toggle');
        if (tbToggle) {
            var tbOpenInput = document.getElementById('tb_open');
            var tbCaret = document.getElementById('tbCaret');
            tbToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.tb-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.tb-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (tbOpenInput) tbOpenInput.value = nextOpen ? '1' : '0';
                if (tbCaret) tbCaret.classList.toggle('rotate-180', nextOpen);
                tbToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var tropisToggle = document.querySelector('.tropis-parent-toggle');
        if (tropisToggle) {
            var tropisOpenInput = document.getElementById('tropis_open');
            var tropisCaret = document.getElementById('tropisCaret');
            tropisToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.tropis-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.tropis-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (tropisOpenInput) tropisOpenInput.value = nextOpen ? '1' : '0';
                if (tropisCaret) tropisCaret.classList.toggle('rotate-180', nextOpen);
                tropisToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var ppokToggle = document.querySelector('.ppok-parent-toggle');
        if (ppokToggle) {
            var ppokOpenInput = document.getElementById('ppok_open');
            var ppokCaret = document.getElementById('ppokCaret');
            ppokToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.ppok-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.ppok-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (ppokOpenInput) ppokOpenInput.value = nextOpen ? '1' : '0';
                if (ppokCaret) ppokCaret.classList.toggle('rotate-180', nextOpen);
                ppokToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var kadarToggle = document.querySelector('.kadar-parent-toggle');
        if (kadarToggle) {
            var kadarOpenInput = document.getElementById('kadar_open');
            var kadarCaret = document.getElementById('kadarCaret');
            kadarToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.kadar-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.kadar-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (kadarOpenInput) kadarOpenInput.value = nextOpen ? '1' : '0';
                if (kadarCaret) kadarCaret.classList.toggle('rotate-180', nextOpen);
                kadarToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var labToggle = document.querySelector('.lab-parent-toggle');
        if (labToggle) {
            var labOpenInput = document.getElementById('lab_open');
            var labCaret = document.getElementById('labCaret');
            labToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.lab-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.lab-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (labOpenInput) labOpenInput.value = nextOpen ? '1' : '0';
                if (labCaret) labCaret.classList.toggle('rotate-180', nextOpen);
                labToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var jantungToggle = document.querySelector('.jantung-parent-toggle');
        if (jantungToggle) {
            var jantungOpenInput = document.getElementById('jantung_open');
            var jantungCaret = document.getElementById('jantungCaret');
            jantungToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.jantung-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.jantung-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (jantungOpenInput) jantungOpenInput.value = nextOpen ? '1' : '0';
                if (jantungCaret) jantungCaret.classList.toggle('rotate-180', nextOpen);
                jantungToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var kusToggle = document.querySelector('.kus-parent-toggle');
        if (kusToggle) {
            var kusOpenInput = document.getElementById('kus_open');
            var kusCaret = document.getElementById('kusCaret');
            kusToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.kus-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.kus-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (kusOpenInput) kusOpenInput.value = nextOpen ? '1' : '0';
                if (kusCaret) kusCaret.classList.toggle('rotate-180', nextOpen);
                kusToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var kparuToggle = document.querySelector('.kparu-parent-toggle');
        if (kparuToggle) {
            var kparuOpenInput = document.getElementById('kparu_open');
            var kparuCaret = document.getElementById('kparuCaret');
            kparuToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.kparu-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.kparu-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (kparuOpenInput) kparuOpenInput.value = nextOpen ? '1' : '0';
                if (kparuCaret) kparuCaret.classList.toggle('rotate-180', nextOpen);
                kparuToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var catinToggle = document.querySelector('.catin-parent-toggle');
        if (catinToggle) {
            var catinOpenInput = document.getElementById('catin_open');
            var catinCaret = document.getElementById('catinCaret');
            catinToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.catin-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.catin-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (catinOpenInput) catinOpenInput.value = nextOpen ? '1' : '0';
                if (catinCaret) catinCaret.classList.toggle('rotate-180', nextOpen);
                catinToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var kpdToggle = document.querySelector('.kpd-parent-toggle');
        if (kpdToggle) {
            var kpdOpenInput = document.getElementById('kpd_open');
            var kpdCaret = document.getElementById('kpdCaret');
            kpdToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.kpd-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.kpd-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (kpdOpenInput) kpdOpenInput.value = nextOpen ? '1' : '0';
                if (kpdCaret) kpdCaret.classList.toggle('rotate-180', nextOpen);
                kpdToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var klrToggle = document.querySelector('.klr-parent-toggle');
        if (klrToggle) {
            var klrOpenInput = document.getElementById('klr_open');
            var klrCaret = document.getElementById('klrCaret');
            klrToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.klr-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.klr-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (klrOpenInput) klrOpenInput.value = nextOpen ? '1' : '0';
                if (klrCaret) klrCaret.classList.toggle('rotate-180', nextOpen);
                klrToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        var cpToggle = document.querySelector('.cp-parent-toggle');
        if (cpToggle) {
            var cpOpenInput = document.getElementById('cp_open');
            var cpCaret = document.getElementById('cpCaret');
            cpToggle.addEventListener('click', function () {
                var isOpen = !document.querySelector('.cp-child-row')?.classList.contains('hidden');
                var nextOpen = !isOpen;
                document.querySelectorAll('.cp-child-row').forEach(function (row) {
                    row.classList.toggle('hidden', !nextOpen);
                });
                if (cpOpenInput) cpOpenInput.value = nextOpen ? '1' : '0';
                if (cpCaret) cpCaret.classList.toggle('rotate-180', nextOpen);
                cpToggle.setAttribute('aria-expanded', nextOpen ? 'true' : 'false');
            });
        }

        [toggleBackdrop, toggleClose, toggleCancel].forEach(function (el) {
            if (!el) return;
            el.addEventListener('click', function () {
                pendingToggle = null;
                closeToggleModal();
            });
        });
        if (toggleOk) {
            toggleOk.addEventListener('click', function () {
                if (!pendingToggle) return closeToggleModal();
                var key = pendingToggle.getAttribute('data-key');
                pendingToggle.checked = pendingNext;
                setRowState(key, pendingNext);
                if (!pendingNext) setNotice(true);
                pendingToggle = null;
                closeToggleModal();
            });
        }
    })();
</script>
@endpush
