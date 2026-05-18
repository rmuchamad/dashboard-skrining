{{-- Modal Formulir Pendaftaran — 2 langkah (identitas + jadwal | data pendukung) --}}
@php
    $pendaftaranStep = '1';
    if ($errors->any()) {
        $pendaftaranStep = $errors->hasAny(['nik', 'name', 'birth_date', 'gender', 'phone', 'ckg_date']) ? '1' : '2';
    } elseif (old('_ps') === '2') {
        $pendaftaranStep = '2';
    }
@endphp
<div id="pendaftaranModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-3 sm:p-4" aria-hidden="true" role="dialog" aria-labelledby="pendaftaranModalTitle">
    <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-[2px]" id="pendaftaranModalBackdrop" tabindex="-1"></div>
    <div class="relative flex max-h-[94vh] w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200">
        <div class="flex shrink-0 items-start justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-7 sm:py-5">
            <div>
                <h2 id="pendaftaranModalTitle" class="modal-title text-xl font-bold text-slate-900 sm:text-2xl">Formulir Pendaftaran</h2>
                <p class="mt-1 text-base text-slate-600">Pastikan isi data sesuai dengan KTP/KK.</p>
            </div>
            <button type="button" id="closePendaftaranModal" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800" aria-label="Tutup">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="shrink-0 px-5 pb-3 pt-2 sm:px-7">
            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                <div id="m_progress_fill" class="h-full rounded-full bg-[#00A99D] transition-all duration-300" style="width: {{ $pendaftaranStep === '2' ? '100%' : '50%' }}"></div>
            </div>
            <p id="m_step_subtitle" class="mt-2 text-sm font-medium text-slate-600">
                @if($pendaftaranStep === '2')
                    Langkah 2 dari 2 — Isi data pendukung
                @else
                    Langkah 1 dari 2 — Isi identitas &amp; jadwal
                @endif
            </p>
        </div>

        <form id="pendaftaranModalForm" method="POST" action="{{ route('screening.step1') }}" class="flex min-h-0 flex-1 flex-col" novalidate>
            @csrf
            <input type="hidden" name="_ps" id="m_ps" value="{{ old('_ps', $pendaftaranStep) }}">
            <input type="hidden" name="update_session_id" id="m_update_session_id" value="">

            <div class="min-h-0 flex-1 overflow-y-auto px-5 py-2 sm:px-7 sm:py-4">
                <div class="mb-5 flex gap-3 rounded-xl border border-amber-200 bg-orange-50 px-4 py-3 text-base text-amber-950">
                    <span class="shrink-0 text-xl leading-none text-amber-600">&#9888;</span>
                    <div>
                        <p class="font-medium">Nama yang diisi harus sama persis dengan Kartu Tanda Penduduk (KTP)</p>
                        <button type="button" class="mt-1 text-base font-semibold text-blue-600 hover:underline" onclick="alert('Gunakan huruf kapital sesuai KTP, tanpa gelar. Contoh: sesuai baris NAMA pada KTP.')">Lihat Petunjuk Pengisian Nama</button>
                    </div>
                </div>

                {{-- Langkah 1 --}}
                <div id="m_panel_step1" class="space-y-4 {{ $pendaftaranStep === '2' ? 'hidden' : '' }}">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Isi identitas</h3>
                        <p class="mt-1 text-base text-slate-600">Silakan lengkapi data peserta dan wali (jika diperlukan).</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-10">
                        <div class="space-y-4">
                            <div>
                                <label class="mb-1.5 block text-base font-semibold text-slate-800">NIK <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2">
                                    <div class="min-w-0 flex-1">
                                        <input id="m_field_nik_input" type="text" name="nik" value="{{ old('nik') }}" maxlength="16" inputmode="numeric" autocomplete="off" placeholder="Masukkan NIK" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('nik') border-rose-500 @enderror" required>
                                        <p class="mt-1 text-sm text-slate-500"><span id="m_nik_len">0</span>/16</p>
                                    </div>
                                    <button class="shrink-0 self-start rounded-lg border border-slate-300 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-800 hover:bg-slate-200" type="button" id="m_btnCheckNik">Cek NIK</button>
                                </div>
                                @error('nik')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                                <label class="mt-3 flex cursor-pointer items-center gap-2 text-base text-slate-700">
                                    <input type="checkbox" id="m_no_nik" class="h-4 w-4 rounded border-slate-300 text-[#00A99D]" disabled title="Sementara wajib NIK sesuai aturan pendaftaran">
                                    <span>Tidak punya NIK</span>
                                </label>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-base font-semibold text-slate-800">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Masukkan nama lengkap" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('name') border-rose-500 @enderror" required>
                                @error('name')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-base font-semibold text-slate-800">Tanggal Lahir <span class="text-rose-500">*</span></label>
                                <input type="date" name="birth_date" id="m_birth_date" value="{{ old('birth_date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('birth_date') border-rose-500 @enderror" required>
                                @error('birth_date')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-base font-semibold text-slate-800">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                <select name="gender" id="m_gender" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('gender') border-rose-500 @enderror" required>
                                    <option value="" disabled @selected(old('gender') === null)>Pilih jenis kelamin</option>
                                    <option value="male" @selected(old('gender') === 'male')>Laki-laki</option>
                                    <option value="female" @selected(old('gender') === 'female')>Perempuan</option>
                                </select>
                                @error('gender')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-base font-semibold text-slate-800">No. Whatsapp Aktif <span class="text-rose-500">*</span></label>
                                <div class="flex rounded-lg border border-slate-300 bg-white focus-within:ring-2 focus-within:ring-[#00A99D]/30">
                                    <span class="flex items-center border-r border-slate-200 bg-slate-50 px-3 py-2.5 text-base font-medium text-slate-700">+62</span>
                                    @php
                                        $phoneLocalOld = '';
                                        if (old('phone')) {
                                            $d = preg_replace('/\D/', '', old('phone'));
                                            if (str_starts_with($d, '62')) {
                                                $d = substr($d, 2);
                                            }
                                            if (str_starts_with($d, '0')) {
                                                $d = substr($d, 1);
                                            }
                                            $phoneLocalOld = $d;
                                        }
                                    @endphp
                                    <input type="tel" id="m_phone_local" value="{{ $phoneLocalOld }}" maxlength="14" inputmode="numeric" placeholder="Masukkan nomor whatsapp" class="min-w-0 flex-1 rounded-r-lg border-0 px-3 py-2.5 text-base outline-none" autocomplete="tel" required>
                                </div>
                                <input type="hidden" name="phone" id="m_phone_hidden" value="{{ old('phone') }}">
                                @error('phone')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                                <div class="mt-3 flex gap-2 rounded-lg border border-blue-100 bg-blue-50 px-3 py-2.5 text-sm text-blue-900">
                                    <span class="shrink-0 text-base">&#9432;</span>
                                    <span>Informasi terkait Cek Kesehatan Gratis akan dikirim ke nomor ini.</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-base font-semibold text-slate-800">Tanggal Pemeriksaan <span class="text-rose-500">*</span></label>
                            <div class="section-card">
                                <div id="m_ckg_inline" class="ckg-inline-cal"></div>
                                <input type="date" id="m_ckg_date_fallback" value="{{ old('ckg_date') }}" class="mt-3 w-full rounded-lg border border-teal-300 bg-white px-3 py-3 text-base text-slate-900">
                                <input type="hidden" name="ckg_date" id="m_ckg_date_hidden" value="{{ old('ckg_date') }}">
                                @error('ckg_date')<div class="mt-2 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Langkah 2 --}}
                <div id="m_panel_step2" class="space-y-6 {{ $pendaftaranStep === '1' ? 'hidden' : '' }}">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Isi data pendukung</h3>
                        <p class="mt-1 text-base text-slate-600">Silakan lengkapi informasi pendukung untuk keperluan layanan CKG.</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-base font-semibold text-slate-800">Pekerjaan <span class="text-rose-500">*</span></label>
                        <select name="work_unit" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('work_unit') border-rose-500 @enderror" required>
                            <option value="" disabled @selected(old('work_unit') === null)>Pilih pekerjaan</option>
                            <option value="PNS" @selected(old('work_unit') === 'PNS')>PNS</option>
                            <option value="TNI/Polri" @selected(old('work_unit') === 'TNI/Polri')>TNI/Polri</option>
                            <option value="BUMN/BUMD" @selected(old('work_unit') === 'BUMN/BUMD')>BUMN/BUMD</option>
                            <option value="Swasta" @selected(old('work_unit') === 'Swasta')>Swasta</option>
                            <option value="Wiraswasta" @selected(old('work_unit') === 'Wiraswasta')>Wiraswasta</option>
                            <option value="Pelajar/Mahasiswa" @selected(old('work_unit') === 'Pelajar/Mahasiswa')>Pelajar/Mahasiswa</option>
                            <option value="Tidak bekerja" @selected(old('work_unit') === 'Tidak bekerja')>Tidak bekerja</option>
                            <option value="Lainnya" @selected(old('work_unit') === 'Lainnya')>Lainnya</option>
                        </select>
                        @error('work_unit')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-base font-semibold text-slate-800">Alamat Domisili <span class="text-rose-500">*</span></label>
                        <p class="mb-2 text-sm text-slate-600">Pilih provinsi hingga kelurahan/desa.</p>

                        <input type="hidden" name="province" id="m_field_province" value="{{ old('province') }}">
                        <input type="hidden" name="province_code" id="m_field_province_code" value="{{ old('province_code') }}">
                        <input type="hidden" name="regency" id="m_field_regency" value="{{ old('regency') }}">
                        <input type="hidden" name="regency_code" id="m_field_regency_code" value="{{ old('regency_code') }}">
                        <input type="hidden" name="district" id="m_field_district" value="{{ old('district') }}">
                        <input type="hidden" name="district_code" id="m_field_district_code" value="{{ old('district_code') }}">
                        <input type="hidden" name="village" id="m_field_village" value="{{ old('village') }}">
                        <input type="hidden" name="village_code" id="m_field_village_code" value="{{ old('village_code') }}">

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <label class="mb-1.5 block text-base font-medium" for="m_wilayah_province">Provinsi <span class="text-rose-500">*</span></label>
                                <select id="m_wilayah_province" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 disabled:bg-slate-100" disabled required>
                                    <option value="">Memuat provinsi...</option>
                                </select>
                                @error('province')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-base font-medium" for="m_wilayah_regency">Kabupaten/Kota <span class="text-rose-500">*</span></label>
                                <select id="m_wilayah_regency" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 disabled:bg-slate-100" disabled required>
                                    <option value="">Pilih provinsi terlebih dahulu</option>
                                </select>
                                @error('regency')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-base font-medium" for="m_wilayah_district">Kecamatan <span class="text-rose-500">*</span></label>
                                <select id="m_wilayah_district" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 disabled:bg-slate-100" disabled required>
                                    <option value="">Pilih kabupaten/kota terlebih dahulu</option>
                                </select>
                                @error('district')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-base font-medium" for="m_wilayah_village">Kelurahan <span class="text-rose-500">*</span></label>
                                <select id="m_wilayah_village" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 disabled:bg-slate-100" disabled required>
                                    <option value="">Pilih kecamatan terlebih dahulu</option>
                                </select>
                                @error('village')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-base font-semibold text-slate-800">Detail Alamat Domisili <span class="text-rose-500">*</span></label>
                        <textarea name="address" rows="3" placeholder="Cth: Jl. Kenanga 14 no 92" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('address') border-rose-500 @enderror" required>{{ old('address') }}</textarea>
                        @error('address')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="border-t border-slate-100 pt-6">
                        <div class="mb-3 text-lg font-bold text-slate-900">Data kepesertaan &amp; pelaksanaan CKG</div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label class="mb-1.5 block text-base font-semibold text-slate-800">Kategori Peserta <span class="text-rose-500">*</span></label>
                                <select name="participant_category" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('participant_category') border-rose-500 @enderror" required>
                                    <option value="" disabled @selected(old('participant_category') === null)>Pilih kategori</option>
                                    <option value="pjlp" @selected(old('participant_category') === 'pjlp')>PJLP</option>
                                    <option value="non_asn" @selected(old('participant_category') === 'non_asn')>Non ASN</option>
                                    <option value="asn" @selected(old('participant_category') === 'asn')>ASN</option>
                                </select>
                                @error('participant_category')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-base font-medium text-slate-800">SKPD</label>
                                <input type="text" name="skpd" value="{{ old('skpd') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25" placeholder="Contoh: Dinas Kesehatan">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-base font-medium text-slate-800">UKPD</label>
                                <input type="text" name="ukpd" value="{{ old('ukpd') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25" placeholder="Contoh: UPT Puskesmas ...">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-base font-medium text-slate-800">No HP/WA Wali</label>
                                <input type="text" name="guardian_phone" value="{{ old('guardian_phone') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25" placeholder="08…">
                            </div>
                            <div>
                                <label class="mb-1.5 block text-base font-medium text-slate-800">Nama Wali</label>
                                <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25" placeholder="Jika diwakilkan">
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1.5 block text-base font-medium">Klinik Pelaksana <span class="text-rose-500">*</span></label>
                                <input type="text" name="clinic_name" value="{{ old('clinic_name') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('clinic_name') border-rose-500 @enderror" required>
                                @error('clinic_name')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1.5 block text-base font-medium">Lokasi CKG <span class="text-rose-500">*</span></label>
                                <input type="text" name="ckg_location" value="{{ old('ckg_location') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-base focus:border-[#00A99D] focus:ring-2 focus:ring-[#00A99D]/25 @error('ckg_location') border-rose-500 @enderror" required>
                                @error('ckg_location')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="m_footer_step1" class="flex shrink-0 flex-wrap items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:px-7 {{ $pendaftaranStep === '2' ? 'hidden' : '' }}">
                <button type="button" id="m_btn_step1_next" class="rounded-lg bg-[#00A99D] px-8 py-2.5 text-base font-semibold text-white shadow-sm hover:bg-[#008f84] disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none" disabled>Selanjutnya</button>
            </div>
            <div id="m_footer_step2" class="flex shrink-0 flex-wrap items-center justify-between gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:px-7 {{ $pendaftaranStep === '1' ? 'hidden' : '' }}">
                <button type="button" id="m_btn_step2_back" class="rounded-lg border-2 border-[#00A99D] bg-white px-6 py-2.5 text-base font-semibold text-[#00A99D] hover:bg-teal-50">Kembali</button>
                <button type="submit" id="btnPendaftaranSubmit" class="rounded-lg bg-[#00A99D] px-8 py-2.5 text-base font-semibold text-white shadow-sm hover:bg-[#008f84] disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none" disabled>Selanjutnya</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal hasil Cek NIK --}}
<div id="nikFoundModal" class="fixed inset-0 z-[210] hidden items-center justify-center p-4" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-slate-900/40" id="nikFoundBackdrop"></div>
    <div class="relative w-full max-w-md rounded-xl border border-slate-200 bg-white p-5 shadow-xl">
        <h5 class="text-lg font-semibold text-slate-800">Data peserta ditemukan</h5>
        <p class="mt-2 text-base text-slate-800">NIK ini sudah terdaftar.</p>
        <p class="text-sm text-slate-600">Klik &quot;Gunakan Data&quot; untuk mengisi form otomatis.</p>
        <div class="mt-4 flex justify-end gap-2">
            <button type="button" id="btnNikModalClose" class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-base font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
            <button type="button" class="rounded-lg bg-[#00A99D] px-5 py-2.5 text-base font-semibold text-white hover:bg-[#008f84]" id="btnUseNikData">Gunakan Data</button>
        </div>
    </div>
</div>

{{-- Modal konfirmasi pendaftaran --}}
<div id="confirmPendaftaranModal" class="fixed inset-0 z-[215] hidden items-center justify-center p-4" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-slate-900/40" id="confirmPendaftaranBackdrop"></div>
    <div class="relative flex max-h-[88vh] w-full max-w-4xl flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
        <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h5 class="text-2xl font-bold text-slate-900">Formulir Pendaftaran</h5>
                <p class="text-sm text-slate-600">Pastikan isi data sesuai dengan KTP/KK.</p>
            </div>
            <button type="button" id="btnConfirmClose" class="rounded p-1.5 text-slate-500 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="min-h-0 flex-1 overflow-y-auto px-5 py-4">
            <h6 class="text-xl font-semibold text-slate-900">List Data Individu</h6>
            <p class="mt-1 text-sm text-slate-600">Berdasarkan data nama, tanggal dan jenis kelamin yang dapat dipilih.</p>
            <div class="candidate-table-wrap mt-4 overflow-x-auto rounded border border-slate-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 text-left text-slate-700">
                        <tr>
                            <th class="px-3 py-2 font-semibold">NIK</th>
                            <th class="px-3 py-2 font-semibold">Nama Individu</th>
                            <th class="px-3 py-2 font-semibold">Tanggal Lahir</th>
                            <th class="px-3 py-2 font-semibold">Jenis Kelamin</th>
                            <th class="px-3 py-2 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="confirmCandidateBody" class="divide-y divide-slate-100"></tbody>
                </table>
            </div>
            <p id="confirmCandidateInfo" class="mt-3 text-xs text-slate-500">Memuat data...</p>
        </div>
        <div class="flex items-center justify-between border-t border-slate-200 px-5 py-3">
            <button type="button" id="btnConfirmBack" class="text-sm font-semibold text-[#00A99D] hover:underline">Kembali</button>
            <button type="button" id="btnConfirmRegisterNik" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300 disabled:cursor-not-allowed disabled:opacity-60" disabled>Daftarkan dengan NIK</button>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <style>
        #pendaftaranModal .modal-title {
            letter-spacing: -0.01em;
        }
        #pendaftaranModal input[type="text"],
        #pendaftaranModal input[type="date"],
        #pendaftaranModal input[type="tel"],
        #pendaftaranModal select,
        #pendaftaranModal textarea {
            transition: border-color .18s ease, box-shadow .18s ease;
        }
        #pendaftaranModal input[type="text"]:focus,
        #pendaftaranModal input[type="date"]:focus,
        #pendaftaranModal input[type="tel"]:focus,
        #pendaftaranModal select:focus,
        #pendaftaranModal textarea:focus {
            border-color: #00A99D;
            box-shadow: 0 0 0 3px rgba(0, 169, 157, 0.16);
            outline: none;
        }
        #pendaftaranModal .section-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            padding: 14px;
        }
        #confirmPendaftaranModal .candidate-row-selected {
            background: #ecfeff;
        }
        #confirmPendaftaranModal .candidate-row-selected td {
            color: #0f172a;
        }
        #confirmPendaftaranModal .candidate-table-wrap {
            border-radius: 10px;
            overflow: hidden;
        }
        .ckg-inline-cal .flatpickr-calendar.inline {
            margin: 0 auto;
            box-shadow: none;
            border: 0;
            width: 100%;
            max-width: 100%;
            border-radius: 0;
        }
        .ckg-inline-cal .flatpickr-months {
            margin-bottom: 4px;
        }
        .ckg-inline-cal .flatpickr-current-month {
            font-size: 16px;
            font-weight: 600;
            color: #334155;
        }
        .ckg-inline-cal .flatpickr-weekday {
            color: #9ca3af;
            font-weight: 500;
        }
        .ckg-inline-cal .flatpickr-day {
            height: 48px;
            max-width: 48px;
            line-height: 1.1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 4px;
            color: #475569;
            font-weight: 600;
            border-radius: 10px;
        }
        .ckg-inline-cal .flatpickr-day .ckg-slot-num {
            font-size: 9px;
            font-weight: 500;
            color: #64748b;
            margin-top: 2px;
            line-height: 1;
        }
        .ckg-inline-cal .flatpickr-day.flatpickr-disabled {
            color: #cbd5e1;
        }
        .ckg-inline-cal .flatpickr-day.flatpickr-disabled .ckg-slot-num {
            display: none;
        }
        .ckg-inline-cal .flatpickr-day.selected,
        .ckg-inline-cal .flatpickr-day.startRange,
        .ckg-inline-cal .flatpickr-day.endRange {
            background: #e6f7f5;
            border-color: #b3ebe6;
            color: #0f172a;
        }
        .ckg-inline-cal .flatpickr-day.selected .ckg-slot-num,
        .ckg-inline-cal .flatpickr-day.startRange .ckg-slot-num {
            color: #0f766e;
        }
        .ckg-inline-cal .flatpickr-day .ckg-slot-num.is-empty {
            color: #ef4444;
        }
    </style>
@endpush

@push('scripts')
    @php
        $wilayahOld = [
            'province_code' => old('province_code'),
            'regency_code' => old('regency_code'),
            'district_code' => old('district_code'),
            'village_code' => old('village_code'),
        ];
    @endphp
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js"></script>
    <script>
        window.__WILAYAH_OLD = @json($wilayahOld);
        window.__WILAYAH_URLS = {
            provinces: @json(url('/api/wilayah/provinces')),
            regenciesBase: @json(url('/api/wilayah/regencies')),
            districtsBase: @json(url('/api/wilayah/districts')),
            villagesBase: @json(url('/api/wilayah/villages')),
        };
        window.__RESPONDENT_URLS = {
            searchCandidates: @json(url('/api/respondent/search-candidates')),
        };
        window.__PENDAFTARAN_INITIAL_STEP = @json($pendaftaranStep);

        window.openPendaftaranModal = function () {
            var el = document.getElementById('pendaftaranModal');
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.add('flex');
            el.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        };

        window.closePendaftaranModal = function () {
            var el = document.getElementById('pendaftaranModal');
            if (!el) return;
            el.classList.add('hidden');
            el.classList.remove('flex');
            el.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        };

        (function () {
            var modal = document.getElementById('pendaftaranModal');
            var backdrop = document.getElementById('pendaftaranModalBackdrop');
            var btnClose = document.getElementById('closePendaftaranModal');
            var navDaftar = document.getElementById('navDaftarBaru');
            var btnIndividuDaftar = document.getElementById('btnDaftarBaruIndividu');

            if (backdrop) backdrop.addEventListener('click', window.closePendaftaranModal);
            if (btnClose) btnClose.addEventListener('click', window.closePendaftaranModal);
            if (navDaftar) navDaftar.addEventListener('click', function (e) {
                e.preventDefault();
                if (updateSessionInput) updateSessionInput.value = '';
                window.openPendaftaranModal();
            });
            if (btnIndividuDaftar) btnIndividuDaftar.addEventListener('click', function (e) {
                e.preventDefault();
                if (updateSessionInput) updateSessionInput.value = '';
                window.openPendaftaranModal();
            });

            var form = document.getElementById('pendaftaranModalForm');
            var nikInput = document.getElementById('m_field_nik_input');
            var nikLen = document.getElementById('m_nik_len');
            var phoneLocal = document.getElementById('m_phone_local');
            var phoneHidden = document.getElementById('m_phone_hidden');
            var btnStep1Next = document.getElementById('m_btn_step1_next');
            var btnStep2Back = document.getElementById('m_btn_step2_back');
            var btnSubmit = document.getElementById('btnPendaftaranSubmit');
            var confirmModal = document.getElementById('confirmPendaftaranModal');
            var confirmBackdrop = document.getElementById('confirmPendaftaranBackdrop');
            var btnConfirmClose = document.getElementById('btnConfirmClose');
            var btnConfirmBack = document.getElementById('btnConfirmBack');
            var btnConfirmRegisterNik = document.getElementById('btnConfirmRegisterNik');
            var candidateBody = document.getElementById('confirmCandidateBody');
            var candidateInfo = document.getElementById('confirmCandidateInfo');
            var panel1 = document.getElementById('m_panel_step1');
            var panel2 = document.getElementById('m_panel_step2');
            var footer1 = document.getElementById('m_footer_step1');
            var footer2 = document.getElementById('m_footer_step2');
            var progressFill = document.getElementById('m_progress_fill');
            var stepSubtitle = document.getElementById('m_step_subtitle');
            var psInput = document.getElementById('m_ps');
            var updateSessionInput = document.getElementById('m_update_session_id');
            var ckgHidden = document.getElementById('m_ckg_date_hidden');
            var ckgFallback = document.getElementById('m_ckg_date_fallback');

            var provinceSel = document.getElementById('m_wilayah_province');
            var regencySel = document.getElementById('m_wilayah_regency');
            var districtSel = document.getElementById('m_wilayah_district');
            var villageSel = document.getElementById('m_wilayah_village');
            var hProvince = document.getElementById('m_field_province');
            var hProvinceCode = document.getElementById('m_field_province_code');
            var hRegency = document.getElementById('m_field_regency');
            var hRegencyCode = document.getElementById('m_field_regency_code');
            var hDistrict = document.getElementById('m_field_district');
            var hDistrictCode = document.getElementById('m_field_district_code');
            var hVillage = document.getElementById('m_field_village');
            var hVillageCode = document.getElementById('m_field_village_code');

            var fpBirth = null;
            var fpCkg = null;
            var submissionConfirmed = false;
            var selectedCandidateNik = '';
            var currentStep = window.__PENDAFTARAN_INITIAL_STEP === '2' ? 2 : 1;

            function syncPhone() {
                var t = (phoneLocal && phoneLocal.value) ? phoneLocal.value.replace(/\D/g, '') : '';
                if (t.startsWith('0')) t = t.slice(1);
                if (phoneHidden) phoneHidden.value = t ? ('0' + t) : '';
            }

            function updateNikLen() {
                if (nikLen && nikInput) nikLen.textContent = String((nikInput.value || '').length);
            }

            function slotForDay(y, m, d) {
                var dt = new Date(y, m, d);
                var dow = dt.getDay();
                if (dow === 0) return 0;
                var seed = y * 10000 + (m + 1) * 100 + d;
                if ((seed % 11) === 0) return 0;
                return 160 + (seed % 140);
            }

            function initFlatpickrs() {
                var idLocale = (typeof flatpickr !== 'undefined' && flatpickr.l10ns && flatpickr.l10ns.id) ? flatpickr.l10ns.id : undefined;
                var birthEl = document.getElementById('m_birth_date');
                if (birthEl) {
                    birthEl.max = new Date().toISOString().slice(0, 10);
                    birthEl.addEventListener('change', function () { refreshStep1Next(); refreshStep2Submit(); });
                }
                var ckgHost = document.getElementById('m_ckg_inline');
                if (ckgHost && typeof flatpickr !== 'undefined' && ckgHidden) {
                    var def = ckgHidden.value || null;
                    fpCkg = flatpickr(ckgHost, {
                        inline: true,
                        dateFormat: 'Y-m-d',
                        locale: idLocale,
                        defaultDate: def,
                        minDate: 'today',
                        onChange: function (dates) {
                            if (dates && dates[0]) {
                                var y = dates[0].getFullYear();
                                var mo = String(dates[0].getMonth() + 1).padStart(2, '0');
                                var da = String(dates[0].getDate()).padStart(2, '0');
                                ckgHidden.value = y + '-' + mo + '-' + da;
                            } else {
                                ckgHidden.value = '';
                            }
                            refreshStep1Next();
                            refreshStep2Submit();
                        },
                        onDayCreate: function (dObj, dStr, fp, dayElem) {
                            if (dayElem.querySelector('.ckg-slot-num')) return;
                            var dt = dayElem.dateObj;
                            if (!dt) return;
                            var y = dt.getFullYear();
                            var m = dt.getMonth();
                            var d = dt.getDate();
                            var today = new Date();
                            today.setHours(0, 0, 0, 0);
                            var cur = new Date(y, m, d);
                            if (cur < today) return;
                            var n = slotForDay(y, m, d);
                            var span = document.createElement('span');
                            span.className = 'ckg-slot-num' + (n === 0 ? ' is-empty' : '');
                            span.textContent = String(n);
                            dayElem.appendChild(span);
                        },
                    });
                    if (def && fpCkg) {
                        ckgHidden.value = def;
                    }
                    if (ckgFallback) {
                        ckgFallback.classList.add('hidden');
                    }
                } else if (ckgFallback && ckgHidden) {
                    ckgFallback.classList.remove('hidden');
                    ckgFallback.addEventListener('change', function () {
                        ckgHidden.value = ckgFallback.value || '';
                        refreshStep1Next();
                        refreshStep2Submit();
                    });
                    ckgHidden.value = ckgFallback.value || ckgHidden.value || '';
                }
            }

            function setStepUi(step) {
                currentStep = step;
                if (psInput) psInput.value = String(step);
                if (progressFill) progressFill.style.width = step === 2 ? '100%' : '50%';
                if (stepSubtitle) {
                    stepSubtitle.textContent = step === 2
                        ? 'Langkah 2 dari 2 — Isi data pendukung'
                        : 'Langkah 1 dari 2 — Isi identitas & jadwal';
                }
                if (panel1) panel1.classList.toggle('hidden', step !== 1);
                if (panel2) panel2.classList.toggle('hidden', step !== 2);
                if (footer1) footer1.classList.toggle('hidden', step !== 1);
                if (footer2) footer2.classList.toggle('hidden', step !== 2);
                refreshStep1Next();
                refreshStep2Submit();
            }

            function prefillModalFromExisting(row) {
                if (!row || !form) return;
                if (updateSessionInput) updateSessionInput.value = row.session_id ? String(row.session_id) : '';
                setIfExists('nik', row.nik);
                setIfExists('name', row.name);
                setIfExists('birth_date', normalizeDateOnly(row.birth_date));
                setIfExists('gender', row.gender);
                setIfExists('work_unit', row.work_unit);
                setIfExists('participant_category', row.participant_category);
                setIfExists('skpd', row.skpd);
                setIfExists('ukpd', row.ukpd);
                setIfExists('guardian_phone', row.guardian_phone);
                setIfExists('guardian_name', row.guardian_name);
                setIfExists('address', row.address);
                setIfExists('clinic_name', row.clinic_name);
                setIfExists('ckg_location', row.ckg_location);

                if (row.phone) {
                    var p = String(row.phone).replace(/\D/g, '');
                    if (p.startsWith('62')) p = p.slice(2);
                    if (p.startsWith('0')) p = p.slice(1);
                    if (phoneLocal) phoneLocal.value = p;
                    syncPhone();
                }

                if (row.ckg_date) {
                    var ckgDateOnly = normalizeDateOnly(row.ckg_date);
                    ckgHidden.value = ckgDateOnly;
                    if (fpCkg) fpCkg.setDate(ckgDateOnly, false, 'Y-m-d');
                    if (ckgFallback) ckgFallback.value = ckgDateOnly;
                } else {
                    ckgHidden.value = '';
                    if (fpCkg) fpCkg.clear();
                    if (ckgFallback) ckgFallback.value = '';
                }

                if (row.province_code) {
                    provinceSel.value = String(row.province_code);
                }
                updateNikLen();
                setStepUi(1);
                window.openPendaftaranModal();

                (async function () {
                    try {
                        if (row.province_code) {
                            syncProvince();
                            await loadRegencies(row.province_code);
                        }
                        if (row.regency_code) {
                            regencySel.value = String(row.regency_code);
                            syncRegency();
                            await loadDistricts(row.regency_code);
                        }
                        if (row.district_code) {
                            districtSel.value = String(row.district_code);
                            syncDistrict();
                            await loadVillages(row.district_code);
                        }
                        if (row.village_code) {
                            villageSel.value = String(row.village_code);
                            syncVillage();
                        }
                    } catch (e) {
                        // ignore load chain failures and keep existing values
                    }
                    refreshStep1Next();
                    refreshStep2Submit();
                })();
            }

            function formatBirthDate(ymd) {
                if (!ymd) return '-';
                var p = String(ymd).split('-');
                if (p.length !== 3) return ymd;
                var month = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                var mi = Number(p[1]) - 1;
                return p[2] + ' ' + (month[mi] || p[1]) + ' ' + p[0];
            }

            function genderLabel(v) {
                return v === 'male' ? 'Laki-laki' : 'Perempuan';
            }

            function normalizeDateOnly(v) {
                if (!v) return '';
                var s = String(v).trim();
                if (s.length >= 10) return s.slice(0, 10);
                return s;
            }

            function openConfirmModal() {
                if (!confirmModal) return;
                selectedCandidateNik = '';
                if (btnConfirmRegisterNik) btnConfirmRegisterNik.disabled = true;
                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
            }

            function closeConfirmModal() {
                if (!confirmModal) return;
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
            }

            function submitFinalForm() {
                if (!selectedCandidateNik) {
                    alert('Pilih salah satu data individu terlebih dahulu.');
                    return;
                }
                if (nikInput) {
                    nikInput.value = selectedCandidateNik;
                    updateNikLen();
                }
                submissionConfirmed = true;
                closeConfirmModal();
                window.closePendaftaranModal();
                if (form) form.requestSubmit();
            }

            function renderCandidates(items) {
                if (!candidateBody || !candidateInfo) return;
                candidateBody.innerHTML = '';
                var list = Array.isArray(items) ? items : [];
                if (list.length === 0) {
                    var trEmpty = document.createElement('tr');
                    trEmpty.innerHTML = '<td colspan="5" class="px-3 py-5 text-center text-sm text-slate-500">Tidak ada data yang cocok.</td>';
                    candidateBody.appendChild(trEmpty);
                    candidateInfo.textContent = 'Menampilkan 0 data';
                    return;
                }
                list.forEach(function (it) {
                    var tr = document.createElement('tr');
                    tr.className = 'candidate-row hover:bg-slate-50';
                    tr.innerHTML =
                        '<td class="px-3 py-2 text-slate-700">' + (it.nik || '-') + '</td>' +
                        '<td class="px-3 py-2 text-slate-700">' + (it.name || '-') + '</td>' +
                        '<td class="px-3 py-2 text-slate-700">' + formatBirthDate(it.birth_date) + '</td>' +
                        '<td class="px-3 py-2 text-slate-700">' + genderLabel(it.gender) + '</td>' +
                        '<td class="px-3 py-2 text-center"><button type="button" class="btn-select-candidate rounded border border-[#00A99D] px-3 py-1 text-xs font-semibold text-[#00A99D] hover:bg-teal-50" data-nik="' + (it.nik || '') + '">Pilih</button></td>';
                    candidateBody.appendChild(tr);
                });
                candidateInfo.textContent = 'Menampilkan ' + list.length + ' data';
            }

            async function loadCandidatesForConfirm() {
                if (!form) return;
                var nameEl = form.querySelector('[name="name"]');
                var birthEl = form.querySelector('[name="birth_date"]');
                var genderEl = form.querySelector('[name="gender"]');
                if (!nameEl || !birthEl || !genderEl) return;
                var draft = {
                    nik: (nikInput && nikInput.value) ? nikInput.value.trim() : '',
                    name: nameEl.value || '',
                    birth_date: birthEl.value || '',
                    gender: genderEl.value || '',
                };
                var qs = new URLSearchParams({
                    name: nameEl.value || '',
                    birth_date: birthEl.value || '',
                    gender: genderEl.value || '',
                });
                if (candidateInfo) candidateInfo.textContent = 'Memuat data...';
                var res = await fetch(window.__RESPONDENT_URLS.searchCandidates + '?' + qs.toString(), { headers: { Accept: 'application/json' } });
                if (!res.ok) throw new Error('Gagal memuat kandidat');
                var data = await res.json();
                var list = Array.isArray(data.items) ? data.items.slice() : [];
                if (draft.nik) {
                    var hasDraftNik = list.some(function (it) { return String(it.nik || '') === draft.nik; });
                    if (!hasDraftNik) {
                        list.unshift(draft);
                    }
                }
                renderCandidates(list);
            }

            function refreshStep1Next() {
                if (!btnStep1Next || !form) return;
                syncPhone();
                var ok =
                    nikInput && nikInput.value.trim().length >= 1 &&
                    form.querySelector('[name="name"]') && form.querySelector('[name="name"]').value.trim() !== '' &&
                    form.querySelector('[name="birth_date"]') && form.querySelector('[name="birth_date"]').value !== '' &&
                    form.querySelector('[name="gender"]') && form.querySelector('[name="gender"]').value !== '' &&
                    phoneHidden && phoneHidden.value.replace(/\D/g, '').length >= 10 &&
                    ckgHidden && ckgHidden.value !== '';
                btnStep1Next.disabled = !ok;
            }

            function refreshStep2Submit() {
                if (!btnSubmit || !form) return;
                syncPhone();
                var wu = form.querySelector('[name="work_unit"]');
                var pc = form.querySelector('[name="participant_category"]');
                var addr = form.querySelector('[name="address"]');
                var clinic = form.querySelector('[name="clinic_name"]');
                var loc = form.querySelector('[name="ckg_location"]');
                var ok =
                    wu && wu.value !== '' &&
                    hProvinceCode && hProvinceCode.value !== '' &&
                    hRegencyCode && hRegencyCode.value !== '' &&
                    hDistrictCode && hDistrictCode.value !== '' &&
                    hVillageCode && hVillageCode.value !== '' &&
                    addr && addr.value.trim() !== '' &&
                    pc && pc.value !== '' &&
                    clinic && clinic.value.trim() !== '' &&
                    loc && loc.value.trim() !== '';
                btnSubmit.disabled = !ok;
            }

            if (nikInput) {
                nikInput.addEventListener('input', function () {
                    updateNikLen();
                    refreshStep1Next();
                });
            }
            updateNikLen();

            if (phoneLocal) phoneLocal.addEventListener('input', function () { refreshStep1Next(); refreshStep2Submit(); });
            syncPhone();
            ['name', 'gender', 'participant_category', 'work_unit', 'address', 'clinic_name', 'ckg_location'].forEach(function (n) {
                var el = form ? form.querySelector('[name="' + n + '"]') : null;
                if (el) el.addEventListener('change', function () { refreshStep1Next(); refreshStep2Submit(); });
                if (el) el.addEventListener('input', function () { refreshStep1Next(); refreshStep2Submit(); });
            });

            if (btnStep1Next) {
                btnStep1Next.addEventListener('click', function () {
                    refreshStep1Next();
                    if (btnStep1Next.disabled) return;
                    setStepUi(2);
                });
            }
            if (btnStep2Back) {
                btnStep2Back.addEventListener('click', function () {
                    setStepUi(1);
                });
            }
            if (confirmBackdrop) confirmBackdrop.addEventListener('click', closeConfirmModal);
            if (btnConfirmClose) btnConfirmClose.addEventListener('click', closeConfirmModal);
            if (btnConfirmBack) btnConfirmBack.addEventListener('click', closeConfirmModal);
            if (btnConfirmRegisterNik) btnConfirmRegisterNik.addEventListener('click', submitFinalForm);
            if (candidateBody) {
                candidateBody.addEventListener('click', function (ev) {
                    var t = ev.target;
                    if (!(t instanceof HTMLElement)) return;
                    if (!t.classList.contains('btn-select-candidate')) return;
                    var nik = t.getAttribute('data-nik') || '';
                    if (!nik) return;
                    selectedCandidateNik = nik;
                    var rows = candidateBody.querySelectorAll('.candidate-row');
                    rows.forEach(function (row) {
                        row.classList.remove('candidate-row-selected');
                    });
                    var buttons = candidateBody.querySelectorAll('.btn-select-candidate');
                    buttons.forEach(function (btn) {
                        btn.classList.remove('bg-[#00A99D]', 'text-white');
                    });
                    var selectedRow = t.closest('tr');
                    if (selectedRow) selectedRow.classList.add('candidate-row-selected');
                    t.classList.add('bg-[#00A99D]', 'text-white');
                    if (btnConfirmRegisterNik) btnConfirmRegisterNik.disabled = false;
                });
            }

            if (form) {
                form.addEventListener('submit', async function (e) {
                    if (currentStep !== 2) {
                        e.preventDefault();
                        return false;
                    }
                    if (!submissionConfirmed) {
                        e.preventDefault();
                        if (btnSubmit && btnSubmit.disabled) return false;
                        try {
                            await loadCandidatesForConfirm();
                            openConfirmModal();
                        } catch (err) {
                            alert('Gagal memuat konfirmasi data individu.');
                        }
                        return false;
                    }
                    if (psInput) psInput.value = '2';
                    syncPhone();
                    if (provinceSel) provinceSel.disabled = false;
                    if (regencySel) regencySel.disabled = false;
                    if (districtSel) districtSel.disabled = false;
                    if (villageSel) villageSel.disabled = false;
                    syncProvince();
                    syncRegency();
                    syncDistrict();
                    syncVillage();
                    submissionConfirmed = false;
                    if (btnSubmit && btnSubmit.disabled) {
                        e.preventDefault();
                        return false;
                    }
                });
            }

            initFlatpickrs();
            setStepUi(currentStep);

            var checkNikBtn = document.getElementById('m_btnCheckNik');
            var useNikDataBtn = document.getElementById('btnUseNikData');
            var nikModal = document.getElementById('nikFoundModal');
            var nikBackdrop = document.getElementById('nikFoundBackdrop');
            var btnNikModalClose = document.getElementById('btnNikModalClose');

            var oldWilayah = window.__WILAYAH_OLD || {};
            var URLS = window.__WILAYAH_URLS;
            var respondentFromNik = null;

            function openNikModal() {
                nikModal.classList.remove('hidden');
                nikModal.classList.add('flex');
            }
            function closeNikModal() {
                nikModal.classList.add('hidden');
                nikModal.classList.remove('flex');
            }
            if (nikBackdrop) nikBackdrop.addEventListener('click', closeNikModal);
            if (btnNikModalClose) btnNikModalClose.addEventListener('click', closeNikModal);

            function placeholderOption(text) {
                var o = document.createElement('option');
                o.value = '';
                o.textContent = text;
                return o;
            }

            function fillSelect(select, items, placeholderText) {
                select.innerHTML = '';
                select.appendChild(placeholderOption(placeholderText));
                (items || []).forEach(function (it) {
                    var o = document.createElement('option');
                    o.value = String(it.id);
                    o.textContent = it.name;
                    select.appendChild(o);
                });
            }

            async function fetchJson(url) {
                var res = await fetch(url, { headers: { Accept: 'application/json' } });
                if (!res.ok) throw new Error('Gagal memuat data wilayah');
                var data = await res.json();
                return Array.isArray(data) ? data : [];
            }

            function syncProvince() {
                if (!provinceSel || !hProvinceCode || !hProvince) return;
                var opt = provinceSel.options[provinceSel.selectedIndex];
                hProvinceCode.value = provinceSel.value || '';
                hProvince.value = opt && provinceSel.value ? opt.textContent.trim() : '';
            }
            function syncRegency() {
                if (!regencySel || !hRegencyCode || !hRegency) return;
                var opt = regencySel.options[regencySel.selectedIndex];
                hRegencyCode.value = regencySel.value || '';
                hRegency.value = opt && regencySel.value ? opt.textContent.trim() : '';
            }
            function syncDistrict() {
                if (!districtSel || !hDistrictCode || !hDistrict) return;
                var opt = districtSel.options[districtSel.selectedIndex];
                hDistrictCode.value = districtSel.value || '';
                hDistrict.value = opt && districtSel.value ? opt.textContent.trim() : '';
            }
            function syncVillage() {
                if (!villageSel || !hVillageCode || !hVillage) return;
                var opt = villageSel.options[villageSel.selectedIndex];
                hVillageCode.value = villageSel.value || '';
                hVillage.value = opt && villageSel.value ? opt.textContent.trim() : '';
            }

            function resetBelowRegency() {
                fillSelect(regencySel, [], 'Pilih provinsi terlebih dahulu');
                regencySel.disabled = true;
                fillSelect(districtSel, [], 'Pilih kabupaten/kota terlebih dahulu');
                districtSel.disabled = true;
                fillSelect(villageSel, [], 'Pilih kecamatan terlebih dahulu');
                villageSel.disabled = true;
                hRegency.value = '';
                hRegencyCode.value = '';
                hDistrict.value = '';
                hDistrictCode.value = '';
                hVillage.value = '';
                hVillageCode.value = '';
            }
            function resetBelowDistrict() {
                fillSelect(districtSel, [], 'Pilih kabupaten/kota terlebih dahulu');
                districtSel.disabled = true;
                fillSelect(villageSel, [], 'Pilih kecamatan terlebih dahulu');
                villageSel.disabled = true;
                hDistrict.value = '';
                hDistrictCode.value = '';
                hVillage.value = '';
                hVillageCode.value = '';
            }
            function resetBelowVillage() {
                fillSelect(villageSel, [], 'Pilih kecamatan terlebih dahulu');
                villageSel.disabled = true;
                hVillage.value = '';
                hVillageCode.value = '';
            }

            async function loadProvinces() {
                provinceSel.disabled = true;
                var data = await fetchJson(URLS.provinces);
                fillSelect(provinceSel, data, 'Pilih provinsi');
                provinceSel.disabled = false;
            }
            async function loadRegencies(provinceId) {
                resetBelowRegency();
                if (!provinceId) return;
                regencySel.disabled = true;
                var data = await fetchJson(URLS.regenciesBase + '/' + encodeURIComponent(provinceId));
                fillSelect(regencySel, data, 'Pilih kabupaten/kota');
                regencySel.disabled = false;
            }
            async function loadDistricts(regencyId) {
                resetBelowDistrict();
                if (!regencyId) return;
                districtSel.disabled = true;
                var data = await fetchJson(URLS.districtsBase + '/' + encodeURIComponent(regencyId));
                fillSelect(districtSel, data, 'Pilih kecamatan');
                districtSel.disabled = false;
            }
            async function loadVillages(districtId) {
                resetBelowVillage();
                if (!districtId) return;
                villageSel.disabled = true;
                var data = await fetchJson(URLS.villagesBase + '/' + encodeURIComponent(districtId));
                fillSelect(villageSel, data, 'Pilih kelurahan/desa');
                villageSel.disabled = false;
            }

            async function restoreFromOld() {
                var pc = oldWilayah.province_code ? String(oldWilayah.province_code) : '';
                var rc = oldWilayah.regency_code ? String(oldWilayah.regency_code) : '';
                var dc = oldWilayah.district_code ? String(oldWilayah.district_code) : '';
                var vc = oldWilayah.village_code ? String(oldWilayah.village_code) : '';
                if (!pc) return;
                provinceSel.value = pc;
                syncProvince();
                await loadRegencies(pc);
                if (rc) {
                    regencySel.value = rc;
                    syncRegency();
                    await loadDistricts(rc);
                    if (dc) {
                        districtSel.value = dc;
                        syncDistrict();
                        await loadVillages(dc);
                        if (vc) {
                            villageSel.value = vc;
                            syncVillage();
                        }
                    }
                }
            }

            provinceSel.addEventListener('change', async function () {
                syncProvince();
                await loadRegencies(provinceSel.value);
                refreshStep2Submit();
            });
            regencySel.addEventListener('change', async function () {
                syncRegency();
                await loadDistricts(regencySel.value);
                refreshStep2Submit();
            });
            districtSel.addEventListener('change', async function () {
                syncDistrict();
                await loadVillages(districtSel.value);
                refreshStep2Submit();
            });
            villageSel.addEventListener('change', function () {
                syncVillage();
                refreshStep2Submit();
            });

            (async function initWilayah() {
                try {
                    await loadProvinces();
                    await restoreFromOld();
                } catch (e) {
                    provinceSel.innerHTML = '';
                    provinceSel.appendChild(placeholderOption('Gagal memuat provinsi'));
                    provinceSel.disabled = true;
                }
                refreshStep2Submit();
            })();

            async function checkNik() {
                var nik = (nikInput.value || '').trim();
                if (!nik) {
                    alert('Isi NIK terlebih dahulu.');
                    return;
                }
                var res = await fetch('/api/respondent/check-nik?nik=' + encodeURIComponent(nik), { headers: { Accept: 'application/json' } });
                var data = await res.json();
                if (!data.exists) {
                    alert('Data peserta belum ada, lanjutkan pendaftaran baru.');
                    return;
                }
                respondentFromNik = data.respondent;
                openNikModal();
            }

            function setIfExists(name, value) {
                var el = form.querySelector('[name="' + name + '"]');
                if (el) el.value = value || '';
            }

            async function applyNikData() {
                if (!respondentFromNik) return;
                setIfExists('name', respondentFromNik.name);
                setIfExists('nik', respondentFromNik.nik);
                setIfExists('birth_date', normalizeDateOnly(respondentFromNik.birth_date));
                if (fpBirth && respondentFromNik.birth_date) {
                    fpBirth.setDate(normalizeDateOnly(respondentFromNik.birth_date), false, 'Y-m-d');
                }
                setIfExists('gender', respondentFromNik.gender);
                setIfExists('participant_category', respondentFromNik.participant_category);
                setIfExists('work_unit', respondentFromNik.work_unit);
                setIfExists('skpd', respondentFromNik.skpd);
                setIfExists('ukpd', respondentFromNik.ukpd);
                if (respondentFromNik.phone) {
                    var p = String(respondentFromNik.phone).replace(/\D/g, '');
                    if (p.startsWith('62')) p = p.slice(2);
                    if (p.startsWith('0')) p = p.slice(1);
                    if (phoneLocal) phoneLocal.value = p;
                    syncPhone();
                }
                setIfExists('guardian_phone', respondentFromNik.guardian_phone);
                setIfExists('guardian_name', respondentFromNik.guardian_name);
                setIfExists('address', respondentFromNik.address);
                setIfExists('clinic_name', respondentFromNik.clinic_name);
                setIfExists('ckg_location', respondentFromNik.ckg_location);
                ckgHidden.value = '';
                if (fpCkg) fpCkg.clear();
                if (ckgFallback) ckgFallback.value = '';
                updateNikLen();
                if (respondentFromNik.province_code) {
                    provinceSel.value = String(respondentFromNik.province_code);
                    syncProvince();
                    await loadRegencies(respondentFromNik.province_code);
                }
                if (respondentFromNik.regency_code) {
                    regencySel.value = String(respondentFromNik.regency_code);
                    syncRegency();
                    await loadDistricts(respondentFromNik.regency_code);
                }
                if (respondentFromNik.district_code) {
                    districtSel.value = String(respondentFromNik.district_code);
                    syncDistrict();
                    await loadVillages(respondentFromNik.district_code);
                }
                if (respondentFromNik.village_code) {
                    villageSel.value = String(respondentFromNik.village_code);
                    syncVillage();
                }
                closeNikModal();
                refreshStep1Next();
                refreshStep2Submit();
            }

            window.prefillPendaftaranFromSession = prefillModalFromExisting;

            if (checkNikBtn) checkNikBtn.addEventListener('click', function () { checkNik().catch(function () { alert('Gagal mengecek NIK.'); }); });
            if (useNikDataBtn) useNikDataBtn.addEventListener('click', function () { applyNikData().catch(function () { alert('Gagal menerapkan data NIK.'); }); });

            @if($errors->any() && (old('nik') !== null || old('name') !== null))
            window.openPendaftaranModal();
            @endif

            @if(request()->routeIs('screening.create'))
            document.addEventListener('DOMContentLoaded', function () { window.openPendaftaranModal(); });
            @endif

            @if(request()->get('daftar') === '1')
            document.addEventListener('DOMContentLoaded', function () { window.openPendaftaranModal(); });
            @endif
        })();
    </script>
@endpush
