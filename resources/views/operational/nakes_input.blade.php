@extends('layouts.app')

@section('content')
    @php($details = is_array($item['details'] ?? null) ? $item['details'] : [])
    <div class="mb-4 rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('pelayanan.detail', $session) }}" class="text-2xl leading-none text-slate-500 hover:text-slate-700">‹</a>
                <h4 class="text-2xl font-bold text-slate-900">Input Data Pelayanan oleh Nakes</h4>
            </div>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-teal-100 px-3 py-1 text-xs font-semibold text-teal-700">{{ $serviceLabel }}</span>
                @if(in_array($serviceKey, ['skrining_gizi', 'skrining_gigi', 'tuberkulosis', 'penyakit_tropis', 'ppok', 'kadar_co', 'laboratorium', 'jantung', 'kanker_usus', 'kanker_paru', 'catin_laki', 'kanker_payudara', 'kanker_leher_rahim', 'catin_perempuan'], true) && !empty($sectionLabel))
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $sectionLabel }}</span>
                @endif
            </div>
        </div>
        <div class="px-4 py-2 text-xs text-slate-600 bg-slate-50 border-t border-slate-100">
            Isi data layanan sesuai hasil pemeriksaan peserta. Simpan untuk kembali ke detail pemeriksaan.
        </div>
    </div>

    <form method="POST" action="{{ route('pelayanan.nakes.input.save', ['session' => $session, 'serviceKey' => $serviceKey]) }}" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        @csrf

        @php($status = $item['status'] ?? 'selesai')
        @if($serviceKey === 'skrining_gizi' && $section === 'antropometri')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Berat Badan (kg) <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        step="0.1"
                        name="details[gizi_bb]"
                        value="{{ old('details.gizi_bb', $details['gizi_bb'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                        required
                    >
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Pengukuran Tinggi Badan (cm) <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        step="0.1"
                        name="details[gizi_tb]"
                        value="{{ old('details.gizi_tb', $details['gizi_tb'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                        required
                    >
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. Pengukuran Lingkar Perut <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        step="0.1"
                        name="details[gizi_lp]"
                        value="{{ old('details.gizi_lp', $details['gizi_lp'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                        required
                    >
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @elseif($serviceKey === 'skrining_gizi' && $section === 'gds')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah Anda pernah dinyatakan diabetes atau kencing manis oleh Dokter? <span class="text-rose-500">*</span></label>
                    @php($gdsDiag = old('details.gds_diagnosed', $details['gds_diagnosed'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[gds_diagnosed]" value="ya" class="h-4 w-4" {{ $gdsDiag === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[gds_diagnosed]" value="tidak" class="h-4 w-4" {{ $gdsDiag === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Gula Darah Sewaktu (GDS) (mg/dl)</label>
                    <input
                        type="number"
                        step="0.1"
                        name="details[gds_nilai]"
                        value="{{ old('details.gds_nilai', $details['gds_nilai'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                    >
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. Gula Darah Sewaktu Kedua (GDS 2). Lakukan jika hasil GDS 1 Prediabetes (140-199mg/dl) atau Hiperglikemia (&ge;200mg/dl) dan BELUM PERNAH didiagnosis Diabetes</label>
                    <input
                        type="number"
                        step="0.1"
                        name="details[gds2_nilai]"
                        value="{{ old('details.gds2_nilai', $details['gds2_nilai'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                    >
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">4. Gula Darah Puasa (GDP) (mg/dl)</label>
                    <input
                        type="number"
                        step="0.1"
                        name="details[gdp_nilai]"
                        value="{{ old('details.gdp_nilai', $details['gdp_nilai'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                    >
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">5. Gula Darah 2 Jam PP (mg/dl)</label>
                    <input
                        type="number"
                        step="0.1"
                        name="details[g2jpp_nilai]"
                        value="{{ old('details.g2jpp_nilai', $details['g2jpp_nilai'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                    >
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @elseif($serviceKey === 'skrining_gizi' && $section === 'td')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah Anda pernah dinyatakan tekanan darah tinggi? <span class="text-rose-500">*</span></label>
                    @php($tdDiag = old('details.td_diagnosed', $details['td_diagnosed'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[td_diagnosed]" value="ya" class="h-4 w-4" {{ $tdDiag === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[td_diagnosed]" value="tidak" class="h-4 w-4" {{ $tdDiag === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Tekanan Darah Sistolik <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        step="1"
                        name="details[td_sistolik_1]"
                        value="{{ old('details.td_sistolik_1', $details['td_sistolik_1'] ?? $details['td_sistolik'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                        required
                    >
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. Tekanan darah diastolik <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        step="1"
                        name="details[td_diastolik_1]"
                        value="{{ old('details.td_diastolik_1', $details['td_diastolik_1'] ?? $details['td_diastolik'] ?? '') }}"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                        required
                    >
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">4. Tekanan Darah Sistolik Ke-2</label>
                    <input
                        type="number"
                        step="1"
                        name="details[td_sistolik_2]"
                        value="{{ old('details.td_sistolik_2', $details['td_sistolik_2'] ?? '') }}"
                        placeholder="Masukan nilai tekanan darah Sistolik Ke-2"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                    >
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">5. Tekanan darah diastolik ke-2</label>
                    <input
                        type="number"
                        step="1"
                        name="details[td_diastolik_2]"
                        value="{{ old('details.td_diastolik_2', $details['td_diastolik_2'] ?? '') }}"
                        placeholder="Masukan nilai tekanan darah Diastolik"
                        class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm"
                    >
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @elseif($serviceKey === 'skrining_gigi' && $section === 'karies_gigi_hilang')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Gigi karies <span class="text-rose-500">*</span></label>
                    @php($gigiKaries = old('details.gigi_karies', $details['gigi_karies'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[gigi_karies]" value="ya" class="h-4 w-4" {{ $gigiKaries === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[gigi_karies]" value="tidak" class="h-4 w-4" {{ $gigiKaries === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Gigi hilang/dicabut <span class="text-rose-500">*</span></label>
                    @php($gigiHilang = old('details.gigi_hilang_dicabut', $details['gigi_hilang_dicabut'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[gigi_hilang_dicabut]" value="ya" class="h-4 w-4" {{ $gigiHilang === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[gigi_hilang_dicabut]" value="tidak" class="h-4 w-4" {{ $gigiHilang === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @elseif($serviceKey === 'skrining_gigi' && $section === 'penyakit_periodontal')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Penyakit Periodontal <span class="text-rose-500">*</span></label>
                    @php($periodontal = old('details.periodontal', $details['periodontal'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[periodontal]" value="ya" class="h-4 w-4" {{ $periodontal === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[periodontal]" value="tidak" class="h-4 w-4" {{ $periodontal === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Gigi Goyang <span class="text-rose-500">*</span></label>
                    @php($gigiGoyang = old('details.gigi_goyang', $details['gigi_goyang'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[gigi_goyang]" value="ya" class="h-4 w-4" {{ $gigiGoyang === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[gigi_goyang]" value="tidak" class="h-4 w-4" {{ $gigiGoyang === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @elseif($serviceKey === 'tuberkulosis' && $section === 'faktor_xray')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah Anda pernah atau sedang mengalami batuk yang tidak sembuh-sembuh? <span class="text-rose-500">*</span></label>
                    @php($tbBatuk = old('details.tb_batuk', $details['tb_batuk'] ?? ''))
                    <select name="details[tb_batuk]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $tbBatuk === '' ? 'selected' : '' }} disabled>Pilih jawaban</option>
                        <option value="lebih_2_minggu" {{ $tbBatuk === 'lebih_2_minggu' ? 'selected' : '' }}>Ya, lebih dari 2 minggu</option>
                        <option value="kurang_2_minggu" {{ $tbBatuk === 'kurang_2_minggu' ? 'selected' : '' }}>Ya, kurang dari 2 minggu</option>
                        <option value="tidak_batuk" {{ $tbBatuk === 'tidak_batuk' ? 'selected' : '' }}>Tidak batuk</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Apakah berat badan Anda turun tanpa penyebab jelas/ BB tidak naik/ nafsu makan turun? <span class="text-rose-500">*</span></label>
                    @php($tbBb = old('details.tb_bb_turun', $details['tb_bb_turun'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_bb_turun]" value="ya" class="h-4 w-4" {{ $tbBb === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_bb_turun]" value="tidak" class="h-4 w-4" {{ $tbBb === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. Apakah Anda mengalami demam hilang timbul tanpa sebab yang jelas? <span class="text-rose-500">*</span></label>
                    @php($tbDemam = old('details.tb_demam', $details['tb_demam'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_demam]" value="ya" class="h-4 w-4" {{ $tbDemam === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_demam]" value="tidak" class="h-4 w-4" {{ $tbDemam === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">4. Apakah Anda mengalami berkeringat di malam hari tanpa kegiatan? <span class="text-rose-500">*</span></label>
                    @php($tbKeringat = old('details.tb_keringat_malam', $details['tb_keringat_malam'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_keringat_malam]" value="ya" class="h-4 w-4" {{ $tbKeringat === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_keringat_malam]" value="tidak" class="h-4 w-4" {{ $tbKeringat === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">5. Apakah pasien memiliki pembesaran kelenjar getah bening? <span class="text-rose-500">*</span></label>
                    @php($tbKelenjar = old('details.tb_kelenjar', $details['tb_kelenjar'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_kelenjar]" value="ya" class="h-4 w-4" {{ $tbKelenjar === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_kelenjar]" value="tidak" class="h-4 w-4" {{ $tbKelenjar === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">6. Apakah dilakukan pemeriksaan radiografi toraks? <span class="text-rose-500">*</span></label>
                    @php($tbRx = old('details.tb_radiografi_toraks', $details['tb_radiografi_toraks'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_radiografi_toraks]" value="ya" class="h-4 w-4" {{ $tbRx === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tb_radiografi_toraks]" value="tidak" class="h-4 w-4" {{ $tbRx === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @elseif($serviceKey === 'tuberkulosis' && $section === 'pemeriksaan_tb')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah anda ada kontak dengan pasien Tuberkulosis (TBC)? <span class="text-rose-500">*</span></label>
                    @php($tbKontak = old('details.tb_kontak_tbc', $details['tb_kontak_tbc'] ?? ''))
                    <select name="details[tb_kontak_tbc]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $tbKontak === '' ? 'selected' : '' }} disabled>Pilih jawaban</option>
                        <option value="tidak_diketahui" {{ $tbKontak === 'tidak_diketahui' ? 'selected' : '' }}>Tidak diketahui</option>
                        <option value="ya" {{ $tbKontak === 'ya' ? 'selected' : '' }}>Ya</option>
                        <option value="tidak" {{ $tbKontak === 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Metode Pemeriksaan (untuk terduga TB) <span class="text-rose-500">*</span></label>
                    @php($tbMetode = old('details.tb_metode_pemeriksaan', $details['tb_metode_pemeriksaan'] ?? ''))
                    <select name="details[tb_metode_pemeriksaan]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $tbMetode === '' ? 'selected' : '' }} disabled>Pilih metode</option>
                        <option value="tidak_dilakukan" {{ $tbMetode === 'tidak_dilakukan' ? 'selected' : '' }}>Tidak dilakukan</option>
                        <option value="tuberkulin" {{ $tbMetode === 'tuberkulin' ? 'selected' : '' }}>Tes tuberkulin / IGRA</option>
                        <option value="dahak" {{ $tbMetode === 'dahak' ? 'selected' : '' }}>Pemeriksaan dahak</option>
                        <option value="radiografi" {{ $tbMetode === 'radiografi' ? 'selected' : '' }}>Radiografi toraks</option>
                        <option value="lainnya" {{ $tbMetode === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @elseif($serviceKey === 'penyakit_tropis' && $section === 'frambusia')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah terdapat lesi kulit (papiloma, ulkus, hiperkeratosis) yang mencurigakan frambusia di daerah endemis atau berisiko? <span class="text-rose-500">*</span></label>
                    @php($trFr = old('details.tropis_frambusia', $details['tropis_frambusia'] ?? ''))
                    <select name="details[tropis_frambusia]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $trFr === '' ? 'selected' : '' }} disabled>Pilih jawaban</option>
                        <option value="tidak_ada" {{ $trFr === 'tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                        <option value="ya" {{ $trFr === 'ya' ? 'selected' : '' }}>Ya</option>
                        <option value="tidak_diketahui" {{ $trFr === 'tidak_diketahui' ? 'selected' : '' }}>Tidak diketahui</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'penyakit_tropis' && $section === 'kusta')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah tubuh anda ada bercak kulit putih atau merah yang tidak/kurang rasa/ kebal saat disentuh panas/dingin, tidak gatal/ tidak nyeri ? <span class="text-rose-500">*</span></label>
                    @php($trKu = old('details.tropis_kusta', $details['tropis_kusta'] ?? ''))
                    <select name="details[tropis_kusta]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $trKu === '' ? 'selected' : '' }} disabled>Pilih jawaban</option>
                        <option value="tidak_ada" {{ $trKu === 'tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                        <option value="ya" {{ $trKu === 'ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'penyakit_tropis' && $section === 'skabies')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah ada koreng/ruam/bentol/kudis bergerombol yang gatal terutama di malam hari walaupun sudah diberi bedak atau lotion? <span class="text-rose-500">*</span></label>
                    @php($trSk = old('details.tropis_skabies', $details['tropis_skabies'] ?? ''))
                    <select name="details[tropis_skabies]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $trSk === '' ? 'selected' : '' }} disabled>Pilih jawaban</option>
                        <option value="tidak_ada" {{ $trSk === 'tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                        <option value="ya" {{ $trSk === 'ya' ? 'selected' : '' }}>Ya</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'ppok' && $section === 'puma')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($rm = old('details.ppok_riwayat_merokok', $details['ppok_riwayat_merokok'] ?? ''))
            @php($bungkus = old('details.ppok_bungkus_tahun', $details['ppok_bungkus_tahun'] ?? ''))
            @php($np = old('details.ppok_napas_pendek', $details['ppok_napas_pendek'] ?? ''))
            @php($dh = old('details.ppok_dahak', $details['ppok_dahak'] ?? ''))
            @php($bt = old('details.ppok_batuk', $details['ppok_batuk'] ?? ''))
            @php($sp = old('details.ppok_spirometri', $details['ppok_spirometri'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah anda sedang/mempunyai riwayat merokok? <span class="text-rose-500">*</span></label>
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_riwayat_merokok]" value="iya" class="h-4 w-4 text-emerald-600" {{ $rm === 'iya' ? 'checked' : '' }} required>
                            <span>Iya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_riwayat_merokok]" value="tidak" class="h-4 w-4 text-emerald-600" {{ $rm === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div id="ppok-bungkus-wrap" class="rounded-md border border-slate-200 bg-slate-50 p-4 {{ $rm !== 'iya' ? 'hidden' : '' }}">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Jika Perokok Aktif, berapa bungkus per tahun?</label>
                    <select id="ppok_bungkus_select" name="details[ppok_bungkus_tahun]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm">
                        <option value="" {{ $bungkus === '' ? 'selected' : '' }} disabled>Pilih rentang</option>
                        <option value="lt_10" {{ $bungkus === 'lt_10' ? 'selected' : '' }}>&lt; 10 bungkus per tahun</option>
                        <option value="10_20" {{ $bungkus === '10_20' ? 'selected' : '' }}>10-20 bungkus per tahun</option>
                        <option value="20_30" {{ $bungkus === '20_30' ? 'selected' : '' }}>20-30 bungkus per tahun</option>
                        <option value="gt_30" {{ $bungkus === 'gt_30' ? 'selected' : '' }}>&gt; 30 bungkus per tahun</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. Apakah Anda pernah merasa napas pendek ketika berjalan lebih cepat pada jalan yang datar atau pada jalan yang sedikit menanjak? <span class="text-rose-500">*</span></label>
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_napas_pendek]" value="ya" class="h-4 w-4 text-emerald-600" {{ $np === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_napas_pendek]" value="tidak" class="h-4 w-4 text-emerald-600" {{ $np === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">4. Apakah Anda biasanya mempunyai dahak yang berasal dari paru atau kesulitan mengeluarkan dahak saat Anda sedang tidak menderita selesma/flu? <span class="text-rose-500">*</span></label>
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_dahak]" value="ya" class="h-4 w-4 text-emerald-600" {{ $dh === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_dahak]" value="tidak" class="h-4 w-4 text-emerald-600" {{ $dh === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">5. Apakah Anda biasanya batuk saat sedang tidak menderita selesma/flu? <span class="text-rose-500">*</span></label>
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_batuk]" value="ya" class="h-4 w-4 text-emerald-600" {{ $bt === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_batuk]" value="tidak" class="h-4 w-4 text-emerald-600" {{ $bt === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">6. Apakah Dokter atau tenaga medis lainnya pernah meminta Anda untuk melakukan pemeriksaan spirometri atau peak flow meter (meniup ke dalam suatu alat) untuk mengetahui fungsi paru? <span class="text-rose-500">*</span></label>
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_spirometri]" value="ya" class="h-4 w-4 text-emerald-600" {{ $sp === 'ya' ? 'checked' : '' }} required>
                            <span>Ya</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[ppok_spirometri]" value="tidak" class="h-4 w-4 text-emerald-600" {{ $sp === 'tidak' ? 'checked' : '' }} required>
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
            @push('scripts')
            <script>
                (function () {
                    var wrap = document.getElementById('ppok-bungkus-wrap');
                    var sel = document.getElementById('ppok_bungkus_select');
                    var radios = document.querySelectorAll('input[name="details[ppok_riwayat_merokok]"]');
                    function sync() {
                        var v = '';
                        radios.forEach(function (r) { if (r.checked) v = r.value; });
                        if (!wrap || !sel) return;
                        if (v === 'iya') {
                            wrap.classList.remove('hidden');
                            sel.setAttribute('required', 'required');
                        } else {
                            wrap.classList.add('hidden');
                            sel.removeAttribute('required');
                            sel.value = '';
                        }
                    }
                    radios.forEach(function (r) { r.addEventListener('change', sync); });
                    sync();
                })();
            </script>
            @endpush
        @elseif($serviceKey === 'kadar_co' && $section === 'pernapasan')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($coPpm = old('details.co_ppm', $details['co_ppm'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Kadar CO Pernapasan <span class="text-rose-500">*</span></label>
                    <input
                        type="number"
                        name="details[co_ppm]"
                        value="{{ $coPpm }}"
                        min="0"
                        max="9999"
                        step="any"
                        inputmode="decimal"
                        autocomplete="off"
                        placeholder="isi dengan hasil pengukuran kadar CO pernapasan (dalam ppm)"
                        class="h-10 w-full rounded-md border border-slate-200 bg-slate-50 px-3 text-sm"
                        required
                    >
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @elseif($serviceKey === 'laboratorium' && $section === 'poct_lipid')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($kol = old('details.lab_kolesterol_total', $details['lab_kolesterol_total'] ?? ''))
            @php($hdl = old('details.lab_hdl', $details['lab_hdl'] ?? ''))
            @php($ldl = old('details.lab_ldl', $details['lab_ldl'] ?? ''))
            @php($tri = old('details.lab_trigliserida', $details['lab_trigliserida'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Kolesterol Total <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_kolesterol_total]" value="{{ $kol }}" step="any" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. HDL <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_hdl]" value="{{ $hdl }}" step="any" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. LDL</label>
                    <input type="number" name="details[lab_ldl]" value="{{ $ldl }}" step="any" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm">
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">4. Trigliserida</label>
                    <input type="number" name="details[lab_trigliserida]" value="{{ $tri }}" step="any" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm">
                </div>
                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'laboratorium' && $section === 'fibrosis_hati')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($sgot = old('details.lab_sgot', $details['lab_sgot'] ?? ''))
            @php($trom = old('details.lab_trombosit', $details['lab_trombosit'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Nilai SGOT <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_sgot]" value="{{ $sgot }}" step="any" placeholder="Isi nilai hasil pemeriksaan SGOT" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Pemeriksaan Trombosit <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_trombosit]" value="{{ $trom }}" step="any" placeholder="Isi hasil pemeriksaan trombosit" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'laboratorium' && $section === 'hepatitis')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($hb = old('details.lab_hepatitis_b', $details['lab_hepatitis_b'] ?? ''))
            @php($hc = old('details.lab_hepatitis_c', $details['lab_hepatitis_c'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Hasil Rapid Test Hepatitis B</label>
                    <select name="details[lab_hepatitis_b]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $hb === '' ? 'selected' : '' }} disabled>Pilih hasil</option>
                        <option value="hbsag_non_reaktif" {{ $hb === 'hbsag_non_reaktif' ? 'selected' : '' }}>HBsAg Non Reaktif</option>
                        <option value="hbsag_reaktif" {{ $hb === 'hbsag_reaktif' ? 'selected' : '' }}>HBsAg Reaktif</option>
                    </select>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Hasil Rapid Test Hepatitis C</label>
                    <select name="details[lab_hepatitis_c]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $hc === '' ? 'selected' : '' }} disabled>Pilih hasil</option>
                        <option value="anti_hcv_non_reaktif" {{ $hc === 'anti_hcv_non_reaktif' ? 'selected' : '' }}>Anti HCV Non Reaktif</option>
                        <option value="anti_hcv_reaktif" {{ $hc === 'anti_hcv_reaktif' ? 'selected' : '' }}>Anti HCV Reaktif</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'laboratorium' && $section === 'fungsi_ginjal_lk')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($kre = old('details.lab_kreatinin', $details['lab_kreatinin'] ?? ''))
            @php($ure = old('details.lab_ureum', $details['lab_ureum'] ?? ''))
            @php($uia = old('details.lab_usia_scr_ginjal', $details['lab_usia_scr_ginjal'] ?? ''))
            @php($elf = old('details.lab_elfg_ckd_epi', $details['lab_elfg_ckd_epi'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Hasil Pemeriksaan Kreatinin <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_kreatinin]" value="{{ $kre }}" step="any" placeholder="Masukkan nilai hasil pemeriksaan kreatinin" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Hasil Pemeriksaan Ureum <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_ureum]" value="{{ $ure }}" step="any" placeholder="Masukkan nilai hasil pemeriksaan Ureum" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. Usia <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_usia_scr_ginjal]" value="{{ $uia }}" step="1" min="0" max="150" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">4. Nilai Hasil pemeriksaan (e-LFG (CKD-EPI)) <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_elfg_ckd_epi]" value="{{ $elf }}" step="any" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'laboratorium' && $section === 'kerusakan_ginjal')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($alb = old('details.lab_albumin_urin', $details['lab_albumin_urin'] ?? ''))
            @php($kru = old('details.lab_kreatinin_urin', $details['lab_kreatinin_urin'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Konsentrasi Albumin Urin <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_albumin_urin]" value="{{ $alb }}" step="any" placeholder="Isi Konsentrasi Albumin Urin" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Konsentrasi Kreatinin Urin <span class="text-rose-500">*</span></label>
                    <input type="number" name="details[lab_kreatinin_urin]" value="{{ $kru }}" step="any" placeholder="Isi Konsentrasi Kreatinin Urin" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                </div>
                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'jantung' && $section === 'hasil_ekg')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($h1 = old('details.jtg_hasil_pemeriksaan_ekg', $details['jtg_hasil_pemeriksaan_ekg'] ?? ''))
            @php($h2 = old('details.jtg_pemeriksaan_ekg', $details['jtg_pemeriksaan_ekg'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Hasil Pemeriksaan EKG</label>
                    <select name="details[jtg_hasil_pemeriksaan_ekg]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $h1 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="normal" {{ $h1 === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="abnormal" {{ $h1 === 'abnormal' ? 'selected' : '' }}>Abnormal</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Pemeriksaan EKG</label>
                    <select name="details[jtg_pemeriksaan_ekg]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $h2 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="normal" {{ $h2 === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="st_depresi" {{ $h2 === 'st_depresi' ? 'selected' : '' }}>Abnormal ST Depresi</option>
                        <option value="t_inversi" {{ $h2 === 't_inversi' ? 'selected' : '' }}>Abnormal T Inversi</option>
                        <option value="hipertrofi_vki" {{ $h2 === 'hipertrofi_vki' ? 'selected' : '' }}>Abnormal Hipertrofi Ventrikel Kiri</option>
                        <option value="atrial_fibrilasi" {{ $h2 === 'atrial_fibrilasi' ? 'selected' : '' }}>Abnormal Atrial Fibrilasi</option>
                        <option value="q_patologis" {{ $h2 === 'q_patologis' ? 'selected' : '' }}>Abnormal Q-Patologis</option>
                        <option value="st_elevasi" {{ $h2 === 'st_elevasi' ? 'selected' : '' }}>Abnormal ST Elevasi</option>
                        <option value="gambaran_lainnya" {{ $h2 === 'gambaran_lainnya' ? 'selected' : '' }}>Abnormal Gambaran Abnormal Lainnya</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'kanker_usus' && $section === 'lanjutan')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($k1 = old('details.ku_kesediaan_colok', $details['ku_kesediaan_colok'] ?? ''))
            @php($k2 = old('details.ku_colok_dubur', $details['ku_colok_dubur'] ?? ''))
            @php($k3 = old('details.ku_darah_samar', $details['ku_darah_samar'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900 text-center">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Kesediaan untuk diperiksa Colok Dubur <span class="text-rose-500">*</span></label>
                    <select id="ku_kesediaan_select" name="details[ku_kesediaan_colok]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $k1 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="bersedia" {{ $k1 === 'bersedia' ? 'selected' : '' }}>Bersedia</option>
                        <option value="menolak" {{ $k1 === 'menolak' ? 'selected' : '' }}>Menolak</option>
                        <option value="tidak_tahu" {{ $k1 === 'tidak_tahu' ? 'selected' : '' }}>Tidak tahu</option>
                    </select>
                </div>

                <div id="ku-opsional-wrap" class="space-y-3 {{ $k1 !== 'bersedia' ? 'hidden' : '' }}">
                    <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                        <label class="mb-2 block text-xs font-semibold text-slate-700">2. Colok Dubur (hanya apabila bersedia)</label>
                        <select id="ku_colok_select" name="details[ku_colok_dubur]" class="ku-opsional-field h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm">
                            <option value="" {{ ($k2 === '' || $k2 === 'tidak_berlaku') ? 'selected' : '' }} disabled>Pilih</option>
                            <option value="ditemukan_benjolan" {{ $k2 === 'ditemukan_benjolan' ? 'selected' : '' }}>Ditemukan benjolan</option>
                            <option value="tidak_ditemukan_benjolan" {{ $k2 === 'tidak_ditemukan_benjolan' ? 'selected' : '' }}>Tidak ditemukan benjolan</option>
                        </select>
                    </div>
                    <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                        <label class="mb-2 block text-xs font-semibold text-slate-700">3. Hasil pemeriksaan Darah Samar (hanya apabila bersedia)</label>
                        <select id="ku_darah_select" name="details[ku_darah_samar]" class="ku-opsional-field h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm">
                            <option value="" {{ ($k3 === '' || $k3 === 'tidak_berlaku') ? 'selected' : '' }} disabled>Pilih</option>
                            <option value="negatif" {{ $k3 === 'negatif' ? 'selected' : '' }}>Negatif</option>
                            <option value="positif" {{ $k3 === 'positif' ? 'selected' : '' }}>Positif</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
            @push('scripts')
            <script>
                (function () {
                    var sel = document.getElementById('ku_kesediaan_select');
                    var wrap = document.getElementById('ku-opsional-wrap');
                    var s2 = document.getElementById('ku_colok_select');
                    var s3 = document.getElementById('ku_darah_select');
                    function sync() {
                        var v = sel ? sel.value : '';
                        if (!wrap || !s2 || !s3) return;
                        if (v === 'bersedia') {
                            wrap.classList.remove('hidden');
                            s2.setAttribute('required', 'required');
                            s3.setAttribute('required', 'required');
                        } else {
                            wrap.classList.add('hidden');
                            s2.removeAttribute('required');
                            s3.removeAttribute('required');
                            s2.value = '';
                            s3.value = '';
                        }
                    }
                    if (sel) sel.addEventListener('change', sync);
                    sync();
                })();
            </script>
            @endpush
        @elseif($serviceKey === 'kanker_paru' && $section === 'usia_45')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($q1 = old('details.kp_q1_diagnosis', $details['kp_q1_diagnosis'] ?? ''))
            @php($q2 = old('details.kp_q2_keluarga', $details['kp_q2_keluarga'] ?? ''))
            @php($q3 = old('details.kp_q3_rokok', $details['kp_q3_rokok'] ?? ''))
            @php($q4 = old('details.kp_q4_karsinogen', $details['kp_q4_karsinogen'] ?? ''))
            @php($q5 = old('details.kp_q5_lingkungan', $details['kp_q5_lingkungan'] ?? ''))
            @php($q6 = old('details.kp_q6_rumah', $details['kp_q6_rumah'] ?? ''))
            @php($q7 = old('details.kp_q7_paru_kronik', $details['kp_q7_paru_kronik'] ?? ''))
            @php($q8 = old('details.kp_q8_foto_torax', $details['kp_q8_foto_torax'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-center text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apakah pernah didiagnosis/menderita kanker? <span class="text-rose-500">*</span></label>
                    <select name="details[kp_q1_diagnosis]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $q1 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="dx_gt5" {{ $q1 === 'dx_gt5' ? 'selected' : '' }}>Memiliki diagnosis kanker &gt; 5 tahun yang lalu</option>
                        <option value="dx_lt5" {{ $q1 === 'dx_lt5' ? 'selected' : '' }}>Memiliki diagnosis kanker &lt; 5 tahun yang lalu</option>
                        <option value="tidak_pernah" {{ $q1 === 'tidak_pernah' ? 'selected' : '' }}>Tidak pernah didiagnosis menderita kanker</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Apakah ada keluarga (ayah/ibu/saudara kandung) didiagnosis/menderita kanker sebelumnya?</label>
                    <select name="details[kp_q2_keluarga]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm">
                        <option value="" {{ $q2 === '' ? 'selected' : '' }}>— (opsional) —</option>
                        <option value="kel_paru" {{ $q2 === 'kel_paru' ? 'selected' : '' }}>Memiliki keluarga yang terdiagnosis kanker paru</option>
                        <option value="kel_lain" {{ $q2 === 'kel_lain' ? 'selected' : '' }}>Memiliki keluarga yang terdiagnosis kanker lain</option>
                        <option value="kel_tidak" {{ $q2 === 'kel_tidak' ? 'selected' : '' }}>Tidak ada keluarga yang terdiagnosis kanker</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. Riwayat merokok/paparan asap rokok <span class="text-rose-500">*</span></label>
                    <select name="details[kp_q3_rokok]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $q3 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="rokok_aktif" {{ $q3 === 'rokok_aktif' ? 'selected' : '' }}>Perokok aktif (dalam 1 tahun ini masih merokok)</option>
                        <option value="rokok_berhenti" {{ $q3 === 'rokok_berhenti' ? 'selected' : '' }}>Perokok/bekas perokok berhenti &lt; 10 tahun lalu</option>
                        <option value="rokok_pasif" {{ $q3 === 'rokok_pasif' ? 'selected' : '' }}>Perokok pasif dari lingkungan rumah/tempat kerja</option>
                        <option value="rokok_tidak" {{ $q3 === 'rokok_tidak' ? 'selected' : '' }}>Tidak pernah merokok</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">4. Riwayat tempat kerja mengandung zat karsinogenik (Pertambangan/ pabrik/ bengkel/ garmen/ bangunan/ laboratorium/ sopir/ galangan kapal, dll)? <span class="text-rose-500">*</span></label>
                    <select name="details[kp_q4_karsinogen]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $q4 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="kars_ya" {{ $q4 === 'kars_ya' ? 'selected' : '' }}>Ya, memiliki tempat kerja mengandung zat karsinogenik</option>
                        <option value="kars_tidak_yakin" {{ $q4 === 'kars_tidak_yakin' ? 'selected' : '' }}>Tidak yakin tempat kerja mengandung zat karsinogenik</option>
                        <option value="kars_tidak" {{ $q4 === 'kars_tidak' ? 'selected' : '' }}>Tidak tempat kerja mengandung zat karsinogenik</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">5. Lingkungan tempat tinggal berpotensi tinggi (lingkungan dekat pabrik/pertambangan/buangan sampah, dll)? <span class="text-rose-500">*</span></label>
                    <select name="details[kp_q5_lingkungan]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $q5 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="tinggi_ya" {{ $q5 === 'tinggi_ya' ? 'selected' : '' }}>Memiliki tempat tinggal berpotensi tinggi</option>
                        <option value="tinggi_tidak_yakin" {{ $q5 === 'tinggi_tidak_yakin' ? 'selected' : '' }}>Tidak yakin memiliki tempat tinggal berpotensi tinggi</option>
                        <option value="tinggi_tidak" {{ $q5 === 'tinggi_tidak' ? 'selected' : '' }}>Tidak memiliki tempat tinggal berpotensi tinggi</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">6. Lingkungan dalam rumah yang tidak sehat (ventilasi buruk/atap dari asbes/lantai tanah, dapur tungku, dll)? <span class="text-rose-500">*</span></label>
                    <select name="details[kp_q6_rumah]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $q6 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="rumah_tidak_sehat" {{ $q6 === 'rumah_tidak_sehat' ? 'selected' : '' }}>Memiliki lingkungan dalam rumah yang tidak sehat</option>
                        <option value="rumah_tidak_yakin" {{ $q6 === 'rumah_tidak_yakin' ? 'selected' : '' }}>Tidak yakin memiliki lingkungan dalam rumah yang tidak sehat</option>
                        <option value="rumah_sehat" {{ $q6 === 'rumah_sehat' ? 'selected' : '' }}>Memiliki lingkungan dalam rumah yang sehat</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">7. Pernah didiagnosis penyakit paru kronik?</label>
                    <select name="details[kp_q7_paru_kronik]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $q7 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="kronik_tbc" {{ $q7 === 'kronik_tbc' ? 'selected' : '' }}>Pernah didiagnosis tuberkulosis (TBC)</option>
                        <option value="kronik_lain" {{ $q7 === 'kronik_lain' ? 'selected' : '' }}>Pernah didiagnosis penyakit kronis lain (PPOK, ILD, dll)</option>
                        <option value="kronik_tidak" {{ $q7 === 'kronik_tidak' ? 'selected' : '' }}>Tidak pernah didiagnosis penyakit paru kronik</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">8. Foto Torax <span class="text-rose-500">*</span></label>
                    <select name="details[kp_q8_foto_torax]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $q8 === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="toraks_normal" {{ $q8 === 'toraks_normal' ? 'selected' : '' }}>Normal</option>
                        <option value="toraks_tidak_normal" {{ $q8 === 'toraks_tidak_normal' ? 'selected' : '' }}>Tidak Normal</option>
                    </select>
                </div>

                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
            </div>
        @elseif($serviceKey === 'catin_laki' && $section === 'hiv')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($hivRapid = old('details.cl_hiv_rapid', $details['cl_hiv_rapid'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-center text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Hasil Pemeriksaan Rapid Test HIV <span class="text-rose-500">*</span></label>
                    <select name="details[cl_hiv_rapid]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $hivRapid === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="reaktif" {{ $hivRapid === 'reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="non_reaktif" {{ $hivRapid === 'non_reaktif' ? 'selected' : '' }}>Non Reaktif</option>
                    </select>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
                <a href="{{ route('pelayanan.detail', $session) }}" class="mt-2 flex h-12 w-full items-center justify-center bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Kembali ke Halaman Utama</a>
            </div>
        @elseif($serviceKey === 'catin_laki' && $section === 'sifilis')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($sifRapid = old('details.cl_sifilis_rapid', $details['cl_sifilis_rapid'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-center text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Hasil Pemeriksaan Rapid Test Sifilis <span class="text-rose-500">*</span></label>
                    <select name="details[cl_sifilis_rapid]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $sifRapid === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="reaktif" {{ $sifRapid === 'reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="non_reaktif" {{ $sifRapid === 'non_reaktif' ? 'selected' : '' }}>Non Reaktif</option>
                    </select>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
                <a href="{{ route('pelayanan.detail', $session) }}" class="mt-2 flex h-12 w-full items-center justify-center bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Kembali ke Halaman Utama</a>
            </div>
        @elseif($serviceKey === 'kanker_payudara' && $section === 'sadanis')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($kpdTindakan = old('details.kpd_tindakan', $details['kpd_tindakan'] ?? ''))
            @php($kpdHasil = old('details.kpd_hasil_sadanis', $details['kpd_hasil_sadanis'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-left text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Pemeriksaan yang dilakukan <span class="text-rose-500">*</span></label>
                    <select name="details[kpd_tindakan]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $kpdTindakan === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="sadanis" {{ $kpdTindakan === 'sadanis' ? 'selected' : '' }}>SADANIS</option>
                    </select>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Hasil pemeriksaan SADANIS <span class="text-rose-500">*</span></label>
                    <select name="details[kpd_hasil_sadanis]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $kpdHasil === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="normal" {{ $kpdHasil === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="tidak_normal" {{ $kpdHasil === 'tidak_normal' ? 'selected' : '' }}>Tidak Normal</option>
                    </select>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
                <a href="{{ route('pelayanan.detail', $session) }}" class="mt-2 flex h-12 w-full items-center justify-center bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Kembali ke Halaman Utama</a>
            </div>
        @elseif($serviceKey === 'kanker_leher_rahim' && $section === 'hpv_dna')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($klrHpv = old('details.klr_hpv_dna', $details['klr_hpv_dna'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-left text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Hasil Pemeriksaan HPV-DNA <span class="text-rose-500">*</span></label>
                    <select name="details[klr_hpv_dna]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $klrHpv === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="negatif" {{ $klrHpv === 'negatif' ? 'selected' : '' }}>Negatif</option>
                        <option value="positif" {{ $klrHpv === 'positif' ? 'selected' : '' }}>Positif</option>
                    </select>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
                <a href="{{ route('pelayanan.detail', $session) }}" class="mt-2 flex h-12 w-full items-center justify-center bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Kembali ke Halaman Utama</a>
            </div>
        @elseif($serviceKey === 'kanker_leher_rahim' && $section === 'inspekulo_iva')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($klrInspekulo = old('details.klr_inspekulo', $details['klr_inspekulo'] ?? ''))
            @php($klrIva = old('details.klr_iva', $details['klr_iva'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-left text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Pemeriksaan Inspekulo <span class="text-rose-500">*</span></label>
                    <select name="details[klr_inspekulo]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $klrInspekulo === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="normal" {{ $klrInspekulo === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="curiga_kanker" {{ $klrInspekulo === 'curiga_kanker' ? 'selected' : '' }}>Curiga kanker</option>
                    </select>
                </div>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Pemeriksaan Inspeksi Visual Asam Asetat (IVA) <span class="text-rose-500">*</span></label>
                    <select name="details[klr_iva]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $klrIva === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="negatif" {{ $klrIva === 'negatif' ? 'selected' : '' }}>Negatif</option>
                        <option value="positif" {{ $klrIva === 'positif' ? 'selected' : '' }}>Positif</option>
                    </select>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
                <a href="{{ route('pelayanan.detail', $session) }}" class="mt-2 flex h-12 w-full items-center justify-center bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Kembali ke Halaman Utama</a>
            </div>
        @elseif($serviceKey === 'catin_perempuan' && $section === 'cp_perempuan')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($cpHb = old('details.cp_hb', $details['cp_hb'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-left text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Kadar Hemoglobin <span class="text-rose-500">*</span></label>
                    <select name="details[cp_hb]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $cpHb === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="hb_normal" {{ $cpHb === 'hb_normal' ? 'selected' : '' }}>Normal</option>
                        <option value="hb_rendah" {{ $cpHb === 'hb_rendah' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
                <a href="{{ route('pelayanan.detail', $session) }}" class="mt-2 flex h-12 w-full items-center justify-center bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Kembali ke Halaman Utama</a>
            </div>
        @elseif($serviceKey === 'catin_perempuan' && $section === 'hiv')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($cpHiv = old('details.cp_hiv_rapid', $details['cp_hiv_rapid'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-left text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Hasil Pemeriksaan Rapid Test HIV <span class="text-rose-500">*</span></label>
                    <select name="details[cp_hiv_rapid]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $cpHiv === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="reaktif" {{ $cpHiv === 'reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="non_reaktif" {{ $cpHiv === 'non_reaktif' ? 'selected' : '' }}>Non Reaktif</option>
                    </select>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
                <a href="{{ route('pelayanan.detail', $session) }}" class="mt-2 flex h-12 w-full items-center justify-center bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Kembali ke Halaman Utama</a>
            </div>
        @elseif($serviceKey === 'catin_perempuan' && $section === 'sifilis')
            <input type="hidden" name="section" value="{{ $section }}">
            <input type="hidden" name="status" value="selesai">
            @php($cpSifilis = old('details.cp_sifilis_rapid', $details['cp_sifilis_rapid'] ?? ''))
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-left text-xl font-bold text-slate-900">{{ $sectionLabel }}</h5>
                <div class="rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Hasil Pemeriksaan Rapid Test Sifilis <span class="text-rose-500">*</span></label>
                    <select name="details[cp_sifilis_rapid]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $cpSifilis === '' ? 'selected' : '' }} disabled>Pilih</option>
                        <option value="reaktif" {{ $cpSifilis === 'reaktif' ? 'selected' : '' }}>Reaktif</option>
                        <option value="non_reaktif" {{ $cpSifilis === 'non_reaktif' ? 'selected' : '' }}>Non Reaktif</option>
                    </select>
                </div>
                <div class="flex justify-center pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">Kirim</button>
                </div>
                <a href="{{ route('pelayanan.detail', $session) }}" class="mt-2 flex h-12 w-full items-center justify-center bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Kembali ke Halaman Utama</a>
            </div>
        @elseif($serviceKey === 'telinga_mata')
            <input type="hidden" name="status" value="selesai">
            <div class="mx-auto max-w-3xl space-y-3">
                <h5 class="text-xl font-bold text-slate-900">{{ $serviceLabel }}</h5>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">1. Apa Hasil Pemeriksaan Telinga Luar (serumen impaksi)? <span class="text-rose-500">*</span></label>
                    @php($tmSerumen = old('details.tm_serumen_impaksi', $details['tm_serumen_impaksi'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tm_serumen_impaksi]" value="tidak_ada" class="h-4 w-4" {{ $tmSerumen === 'tidak_ada' ? 'checked' : '' }} required>
                            <span>Tidak ada serumen impaksi</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tm_serumen_impaksi]" value="ada" class="h-4 w-4" {{ $tmSerumen === 'ada' ? 'checked' : '' }} required>
                            <span>Ada serumen impaksi</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">2. Apa Hasil Pemeriksaan Telinga Luar (infeksi telinga)? <span class="text-rose-500">*</span></label>
                    @php($tmInfeksi = old('details.tm_infeksi_telinga', $details['tm_infeksi_telinga'] ?? ''))
                    <select name="details[tm_infeksi_telinga]" class="h-10 w-full rounded-md border border-slate-200 bg-white px-3 text-sm" required>
                        <option value="" {{ $tmInfeksi === '' ? 'selected' : '' }} disabled>Pilih hasil infeksi telinga</option>
                        <option value="tidak_ada" {{ $tmInfeksi === 'tidak_ada' ? 'selected' : '' }}>Tidak ada infeksi telinga</option>
                        <option value="diduga" {{ $tmInfeksi === 'diduga' ? 'selected' : '' }}>Diduga ada infeksi telinga</option>
                    </select>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">3. Hasil pemeriksaan tajam pendengaran <span class="text-rose-500">*</span></label>
                    @php($tmDengar = old('details.tm_tajam_pendengaran', $details['tm_tajam_pendengaran'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tm_tajam_pendengaran]" value="normal" class="h-4 w-4" {{ $tmDengar === 'normal' ? 'checked' : '' }} required>
                            <span>Normal</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tm_tajam_pendengaran]" value="gangguan" class="h-4 w-4" {{ $tmDengar === 'gangguan' ? 'checked' : '' }} required>
                            <span>Curiga gangguan pendengaran</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">4. Apa hasil skrining tajam penglihatan? <span class="text-rose-500">*</span></label>
                    @php($tmLihat = old('details.tm_tajam_penglihatan', $details['tm_tajam_penglihatan'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tm_tajam_penglihatan]" value="normal" class="h-4 w-4" {{ $tmLihat === 'normal' ? 'checked' : '' }} required>
                            <span>Normal (visus 6/6 - 6/12)</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tm_tajam_penglihatan]" value="gangguan" class="h-4 w-4" {{ $tmLihat === 'gangguan' ? 'checked' : '' }} required>
                            <span>Curiga gangguan penglihatan (visus &lt; 6/12)</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <label class="mb-2 block text-xs font-semibold text-slate-700">5. Hasil pemeriksaan pupil <span class="text-rose-500">*</span></label>
                    @php($tmPupil = old('details.tm_pupil', $details['tm_pupil'] ?? ''))
                    <div class="space-y-2 text-sm text-slate-700">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tm_pupil]" value="katarak" class="h-4 w-4" {{ $tmPupil === 'katarak' ? 'checked' : '' }} required>
                            <span>Curiga Katarak</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="details[tm_pupil]" value="normal" class="h-4 w-4" {{ $tmPupil === 'normal' ? 'checked' : '' }} required>
                            <span>Normal</span>
                        </label>
                    </div>
                </div>

                <div class="pt-2">
                    <button class="h-10 rounded-md bg-[#00A99D] px-8 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                        Kirim
                    </button>
                </div>
            </div>
        @else
            <div class="mb-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700">Nama Peserta</label>
                    <input value="{{ $session->respondent?->name }}" class="h-10 w-full rounded-md border border-slate-300 bg-slate-50 px-3 text-sm" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-700">Nomor Tiket</label>
                    <input value="{{ $session->ticket_number }}" class="h-10 w-full rounded-md border border-slate-300 bg-slate-50 px-3 text-sm" readonly>
                </div>
            </div>

            <div class="mb-4 rounded-lg border border-slate-200 p-3">
                <p class="mb-2 text-xs font-semibold text-slate-700">Status pemeriksaan layanan</p>
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="status" value="belum" class="h-4 w-4" {{ $status === 'belum' ? 'checked' : '' }}>
                        <span>Belum diperiksa</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="status" value="sedang" class="h-4 w-4" {{ $status === 'sedang' ? 'checked' : '' }}>
                        <span>Sedang diperiksa</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="status" value="selesai" class="h-4 w-4" {{ $status === 'selesai' ? 'checked' : '' }}>
                        <span>Selesai diperiksa</span>
                    </label>
                </div>
            </div>

            @if(in_array($serviceKey, ['skrining_gizi', 'skrining_gigi', 'tuberkulosis', 'penyakit_tropis', 'ppok', 'kadar_co', 'laboratorium', 'jantung', 'kanker_usus', 'kanker_paru', 'catin_laki', 'kanker_payudara', 'kanker_leher_rahim', 'catin_perempuan'], true))
                <input type="hidden" name="section" value="{{ $section }}">
            @endif
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                @foreach($fields as $idx => $label)
                    @php($fKey = 'f_'.$idx)
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-700">{{ $label }}</label>
                        <input
                            type="text"
                            name="details[{{ $fKey }}]"
                            value="{{ old('details.'.$fKey, $details[$fKey] ?? '') }}"
                            class="h-10 w-full rounded-md border border-slate-300 px-3 text-sm"
                        >
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <label class="mb-1 block text-xs font-semibold text-slate-700">Catatan tambahan</label>
                <textarea name="catatan" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('catatan', $item['catatan'] ?? '') }}</textarea>
            </div>

            <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-200 pt-3">
                <a href="{{ route('pelayanan.detail', $session) }}" class="inline-flex h-10 items-center rounded-md border border-slate-300 px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Kembali
                </a>
                <button class="h-10 rounded-md bg-[#00A99D] px-4 text-sm font-semibold text-white hover:bg-[#008f84]" type="submit">
                    Simpan Data Layanan
                </button>
            </div>
        @endif
    </form>
@endsection

