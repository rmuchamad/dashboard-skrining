@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold tracking-tight text-slate-800">Cek Kesehatan Gratis</h2>
    <div class="mt-1 text-base text-slate-600 mb-4">Cek Kesehatan Gratis - Cari/Daftarkan individu</div>

    <div class="mb-4 rounded-lg border border-slate-200 bg-white p-5">
        <div class="mb-4 flex items-start gap-4">
            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-indigo-500 text-lg text-white">🔍</div>
            <div>
                <h3 class="text-2xl font-bold leading-tight text-slate-900">Cari/Daftarkan Individu</h3>
                <div class="mt-2 text-base text-slate-600">Petugas dapat melakukan pencarian individu yang sudah mendaftar melalui SSM WhatsApp atau registrasi langsung di ASIK.</div>
            </div>
        </div>

        <form method="GET" action="{{ route('individu.index') }}" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_140px_1fr_auto_auto]">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-600">Tanggal mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-base">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-600">Tanggal akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-base">
            </div>
            <select name="filter_type" class="h-11 self-end rounded-lg border border-slate-300 px-3 text-base">
                <option value="ticket">Nomor Tiket</option>
                <option value="nik">NIK</option>
                <option value="name">Nama</option>
            </select>
            <input type="text" name="ticket_number" value="{{ request('ticket_number') ?: request('nik') ?: request('name') }}" class="h-11 min-w-0 self-end rounded-lg border border-slate-300 px-3 text-base sm:col-span-2 xl:col-span-1" placeholder="Nomor tiket / NIK / nama">
            <button type="submit" class="h-11 shrink-0 self-end rounded-lg border-2 border-blue-500 px-4 text-base font-semibold text-blue-600">Cari</button>
            <button type="button" id="btnDaftarBaruIndividu" class="grid h-11 shrink-0 place-items-center self-end rounded-lg bg-blue-600 px-4 text-base font-semibold text-white">+ Daftar Baru</button>
        </form>
    </div>

    <h3 class="mb-3 text-2xl font-bold text-slate-900">Data Individu Terdaftar</h3>
    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-base">
                <thead>
                <tr>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">No</th>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">Tanggal Pemeriksaan</th>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">Nama Peserta</th>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">Nama Wali</th>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">Tanggal Lahir</th>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">Jenis Kelamin</th>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">Aksi</th>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">Nomor WhatsApp Peserta</th>
                    <th class="bg-slate-100 px-3 py-3 text-left text-sm font-semibold text-slate-700">Nomor WhatsApp Wali</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sessions as $session)
                    <tr class="border-t border-slate-100">
                        <td class="px-3 py-3">{{ $sessions->firstItem() + $loop->index }}</td>
                        <td class="px-3 py-3">{{ optional($session->screened_at)->format('d M Y') }}</td>
                        <td class="px-3 py-3">{{ $session->respondent?->name }}</td>
                        <td class="px-3 py-3">{{ $session->respondent?->guardian_name ?: '—' }}</td>
                        <td class="px-3 py-3">{{ optional($session->respondent?->birth_date)->format('d M Y') }}</td>
                        <td class="px-3 py-3">{{ $session->respondent?->gender === 'male' ? 'Laki-Laki' : 'Perempuan' }}</td>
                        <td class="px-3 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                @if($session->attendance_status === 'sudah_hadir')
                                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600"><span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>Sudah Hadir</span>
                                @else
                                    <button
                                        type="button"
                                        class="btn-open-confirm-hadir h-9 rounded-lg bg-emerald-600 px-3 text-sm font-semibold text-white"
                                        data-action="{{ route('individu.confirm', $session) }}"
                                        data-name="{{ $session->respondent?->name }}"
                                        data-gender="{{ $session->respondent?->gender === 'male' ? 'Laki-Laki' : 'Perempuan' }}"
                                        data-birth="{{ optional($session->respondent?->birth_date)->format('d F Y') }}"
                                        data-ticket="{{ $session->ticket_number }}"
                                        data-default-date="{{ optional($session->screened_at)->format('Y-m-d') }}"
                                    >
                                        Konfirmasi Hadir
                                    </button>
                                @endif
                                <button
                                    type="button"
                                    class="btn-open-ganti-tanggal h-9 rounded-lg border border-slate-300 px-3 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                                    data-session-id="{{ $session->id }}"
                                    data-nik="{{ $session->respondent?->nik }}"
                                    data-name="{{ $session->respondent?->name }}"
                                    data-birth-date="{{ optional($session->respondent?->birth_date)->format('Y-m-d') }}"
                                    data-gender="{{ $session->respondent?->gender }}"
                                    data-phone="{{ $session->respondent?->phone }}"
                                    data-work-unit="{{ $session->respondent?->work_unit }}"
                                    data-participant-category="{{ $session->respondent?->participant_category }}"
                                    data-skpd="{{ $session->respondent?->skpd }}"
                                    data-ukpd="{{ $session->respondent?->ukpd }}"
                                    data-guardian-phone="{{ $session->respondent?->guardian_phone }}"
                                    data-guardian-name="{{ $session->respondent?->guardian_name }}"
                                    data-province-code="{{ $session->respondent?->province_code }}"
                                    data-regency-code="{{ $session->respondent?->regency_code }}"
                                    data-district-code="{{ $session->respondent?->district_code }}"
                                    data-village-code="{{ $session->respondent?->village_code }}"
                                    data-address="{{ $session->respondent?->address }}"
                                    data-clinic-name="{{ $session->respondent?->clinic_name }}"
                                    data-ckg-location="{{ $session->respondent?->ckg_location }}"
                                    data-ckg-date="{{ optional($session->screened_at)->format('Y-m-d') }}"
                                >
                                    Ganti Tanggal
                                </button>
                            </div>
                        </td>
                        <td class="px-3 py-3">
                            @php
                                $hp = $session->respondent?->phone;
                                $waNum = $hp ? preg_replace('/\D+/', '', $hp) : '';
                                if ($waNum !== '') {
                                    if (str_starts_with($waNum, '0')) {
                                        $waNum = '62'.substr($waNum, 1);
                                    } elseif (! str_starts_with($waNum, '62')) {
                                        $waNum = '62'.$waNum;
                                    }
                                }
                            @endphp
                            @if($hp)
                                <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener" class="font-medium text-emerald-700 underline decoration-emerald-600/40 hover:text-emerald-800">{{ $hp }}</a>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-3 py-3">
                            @php
                                $gw = $session->respondent?->guardian_phone;
                                $wagNum = $gw ? preg_replace('/\D+/', '', $gw) : '';
                                if ($wagNum !== '') {
                                    if (str_starts_with($wagNum, '0')) {
                                        $wagNum = '62'.substr($wagNum, 1);
                                    } elseif (! str_starts_with($wagNum, '62')) {
                                        $wagNum = '62'.$wagNum;
                                    }
                                }
                            @endphp
                            @if($gw)
                                <a href="https://wa.me/{{ $wagNum }}" target="_blank" rel="noopener" class="font-medium text-emerald-700 underline decoration-emerald-600/40 hover:text-emerald-800">{{ $gw }}</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="py-10 text-center text-base text-slate-500">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-t bg-white p-4">
            <div class="text-base text-slate-600">
                Menampilkan {{ $sessions->firstItem() ?? 0 }}-{{ $sessions->lastItem() ?? 0 }} dari {{ $sessions->total() }} data
            </div>
            <div>{{ $sessions->onEachSide(1)->links('pagination::tailwind-numeric') }}</div>
        </div>
    </div>

    <div id="confirmHadirModal" class="fixed inset-0 z-[210] hidden items-center justify-center p-4" aria-hidden="true" role="dialog" aria-labelledby="confirmHadirTitle">
        <div id="confirmHadirBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
        <div class="relative w-full max-w-2xl rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-center justify-end px-4 pt-3">
                <button type="button" id="confirmHadirClose" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-6 pb-6">
                <div class="mb-3 text-center">
                    <div class="mx-auto mb-2 grid h-14 w-14 place-items-center rounded-full bg-amber-400 text-3xl font-bold text-white">!</div>
                    <h4 id="confirmHadirTitle" class="text-xl font-bold text-slate-900">Tandai Hadir?</h4>
                    <p class="mt-1 text-sm text-slate-500">Individu akan dilayani pada tanggal kehadiran yang dipilih.</p>
                </div>

                <form id="confirmHadirForm" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                            <div class="mb-2 text-sm font-semibold text-slate-800">Data Peserta</div>
                            <p>Nama: <span id="ch_name">-</span></p>
                            <p>Jenis Kelamin: <span id="ch_gender">-</span></p>
                            <p>Tanggal Lahir: <span id="ch_birth">-</span></p>
                            <p>Nomor Tiket: <span id="ch_ticket">-</span></p>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-800">Tanggal Kehadiran</label>
                            <input id="ch_attendance_date" type="date" name="attendance_date" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-base" required>
                        </div>
                    </div>

                    <label class="flex items-start gap-2 text-sm text-slate-700">
                        <input id="ch_ack" type="checkbox" name="attendance_ack" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-emerald-600">
                        <span>Peserta memahami dan bersedia mengikuti prosedur CKG.</span>
                    </label>

                    <div class="flex items-center justify-between gap-3 border-t border-slate-200 pt-4">
                        <button type="button" id="confirmHadirCancel" class="h-10 rounded-lg border border-[#00A99D] px-5 text-sm font-semibold text-[#00A99D] hover:bg-teal-50">Batal</button>
                        <button type="submit" id="confirmHadirSubmit" class="h-10 rounded-lg bg-slate-200 px-6 text-sm font-semibold text-slate-500" disabled>Hadir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="gantiTanggalNoticeModal" class="fixed inset-0 z-[212] hidden items-center justify-center p-4" role="dialog" aria-modal="true">
        <div id="gantiTanggalNoticeBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
        <div class="relative w-full max-w-md rounded-xl border border-slate-200 bg-white px-5 py-4 shadow-2xl">
            <button type="button" id="gantiTanggalNoticeClose" class="absolute right-3 top-3 rounded p-1 text-slate-500 hover:bg-slate-100">×</button>
            <div class="text-center">
                <div class="mx-auto mb-2 grid h-14 w-14 place-items-center rounded-full bg-amber-400 text-3xl font-bold text-white">!</div>
                <h4 class="text-xl font-bold text-slate-900">Ganti Tanggal?</h4>
            </div>
            <p class="mt-3 text-sm text-slate-600">
                Jika ada perubahan pada data diri, selain nomor Whatsapp atau tanggal pemeriksaan, maka skrining mandiri yang sudah diisi akan hilang.
            </p>
            <div class="mt-4 grid grid-cols-2 gap-2">
                <button type="button" id="gantiTanggalNoticeBack" class="h-10 rounded-lg border border-[#00A99D] text-sm font-semibold text-[#00A99D] hover:bg-teal-50">Kembali</button>
                <button type="button" id="gantiTanggalNoticeProceed" class="h-10 rounded-lg bg-[#00A99D] text-sm font-semibold text-white hover:bg-[#008f84]">Ganti Tanggal</button>
            </div>
        </div>
    </div>

    @if(session('attendance_popup'))
        <div id="attendanceSuccessModal" class="fixed inset-0 z-[220] flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div id="attendanceSuccessBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
            <div class="relative w-full max-w-md rounded-xl border border-slate-200 bg-white px-6 py-5 shadow-2xl">
                <button type="button" id="attendanceSuccessClose" class="absolute right-3 top-3 rounded p-1 text-slate-500 hover:bg-slate-100">×</button>
                <div class="text-center">
                    <div class="mx-auto mb-3 grid h-16 w-16 place-items-center rounded-full bg-emerald-500 text-4xl font-bold text-white">✓</div>
                    <h4 class="text-2xl font-bold text-slate-900">Berhasil Hadir</h4>
                    <p class="mt-1 text-lg font-semibold text-slate-800">No. Tiket: {{ session('attendance_popup.ticket') }}</p>
                </div>

                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-medium text-amber-800">
                    ⚠ Pemeriksaan mandiri belum lengkap diisi
                </div>

                <p class="mt-3 text-center text-sm text-slate-600">
                    Ingatkan individu untuk melengkapi pemeriksaan mandiri sebelum pelayanan atau bantu isi skrining mandiri.
                </p>

                <div class="mt-4 flex items-center justify-center gap-2">
                    <a href="{{ route('screening.create') }}" class="rounded-lg border border-[#00A99D] px-3 py-2 text-xs font-semibold text-[#00A99D] hover:bg-teal-50">Bantu Isi Skrining Mandiri</a>
                    <button type="button" id="attendanceSuccessOk" class="rounded-lg bg-[#00A99D] px-4 py-2 text-xs font-semibold text-white hover:bg-[#008f84]">Tutup</button>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    (function () {
        var modal = document.getElementById('confirmHadirModal');
        var backdrop = document.getElementById('confirmHadirBackdrop');
        var btnClose = document.getElementById('confirmHadirClose');
        var btnCancel = document.getElementById('confirmHadirCancel');
        var form = document.getElementById('confirmHadirForm');
        var btnSubmit = document.getElementById('confirmHadirSubmit');
        var ack = document.getElementById('ch_ack');
        var attendanceDate = document.getElementById('ch_attendance_date');

        function openModal() {
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        }

        function refreshSubmit() {
            var ready = !!(ack && ack.checked && attendanceDate && attendanceDate.value);
            if (!btnSubmit) return;
            btnSubmit.disabled = !ready;
            btnSubmit.classList.toggle('bg-emerald-600', ready);
            btnSubmit.classList.toggle('text-white', ready);
            btnSubmit.classList.toggle('hover:bg-emerald-700', ready);
            btnSubmit.classList.toggle('bg-slate-200', !ready);
            btnSubmit.classList.toggle('text-slate-500', !ready);
        }

        document.querySelectorAll('.btn-open-confirm-hadir').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!form) return;
                form.action = btn.getAttribute('data-action') || '';
                document.getElementById('ch_name').textContent = btn.getAttribute('data-name') || '-';
                document.getElementById('ch_gender').textContent = btn.getAttribute('data-gender') || '-';
                document.getElementById('ch_birth').textContent = btn.getAttribute('data-birth') || '-';
                document.getElementById('ch_ticket').textContent = btn.getAttribute('data-ticket') || '-';
                attendanceDate.value = btn.getAttribute('data-default-date') || '';
                ack.checked = false;
                refreshSubmit();
                openModal();
            });
        });

        if (backdrop) backdrop.addEventListener('click', closeModal);
        if (btnClose) btnClose.addEventListener('click', closeModal);
        if (btnCancel) btnCancel.addEventListener('click', closeModal);
        if (ack) ack.addEventListener('change', refreshSubmit);
        if (attendanceDate) attendanceDate.addEventListener('change', refreshSubmit);
    })();

    (function () {
        var modal = document.getElementById('gantiTanggalNoticeModal');
        if (!modal) return;
        var backdrop = document.getElementById('gantiTanggalNoticeBackdrop');
        var btnClose = document.getElementById('gantiTanggalNoticeClose');
        var btnBack = document.getElementById('gantiTanggalNoticeBack');
        var btnProceed = document.getElementById('gantiTanggalNoticeProceed');
        var selectedPayload = null;

        function openNotice() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeNotice() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function makePayloadFromButton(btn) {
            return {
                session_id: btn.getAttribute('data-session-id') || '',
                nik: btn.getAttribute('data-nik') || '',
                name: btn.getAttribute('data-name') || '',
                birth_date: btn.getAttribute('data-birth-date') || '',
                gender: btn.getAttribute('data-gender') || '',
                phone: btn.getAttribute('data-phone') || '',
                work_unit: btn.getAttribute('data-work-unit') || '',
                participant_category: btn.getAttribute('data-participant-category') || '',
                skpd: btn.getAttribute('data-skpd') || '',
                ukpd: btn.getAttribute('data-ukpd') || '',
                guardian_phone: btn.getAttribute('data-guardian-phone') || '',
                guardian_name: btn.getAttribute('data-guardian-name') || '',
                province_code: btn.getAttribute('data-province-code') || '',
                regency_code: btn.getAttribute('data-regency-code') || '',
                district_code: btn.getAttribute('data-district-code') || '',
                village_code: btn.getAttribute('data-village-code') || '',
                address: btn.getAttribute('data-address') || '',
                clinic_name: btn.getAttribute('data-clinic-name') || '',
                ckg_location: btn.getAttribute('data-ckg-location') || '',
                ckg_date: btn.getAttribute('data-ckg-date') || '',
            };
        }

        document.querySelectorAll('.btn-open-ganti-tanggal').forEach(function (btn) {
            btn.addEventListener('click', function () {
                selectedPayload = makePayloadFromButton(btn);
                openNotice();
            });
        });

        if (btnProceed) {
            btnProceed.addEventListener('click', function () {
                if (!selectedPayload) return;
                closeNotice();
                if (typeof window.prefillPendaftaranFromSession === 'function') {
                    window.prefillPendaftaranFromSession(selectedPayload);
                }
            });
        }
        if (backdrop) backdrop.addEventListener('click', closeNotice);
        if (btnClose) btnClose.addEventListener('click', closeNotice);
        if (btnBack) btnBack.addEventListener('click', closeNotice);
    })();

    (function () {
        var modal = document.getElementById('attendanceSuccessModal');
        if (!modal) return;
        var closeBtn = document.getElementById('attendanceSuccessClose');
        var okBtn = document.getElementById('attendanceSuccessOk');
        var backdrop = document.getElementById('attendanceSuccessBackdrop');
        function closeSuccessModal() {
            modal.remove();
        }
        if (closeBtn) closeBtn.addEventListener('click', closeSuccessModal);
        if (okBtn) okBtn.addEventListener('click', closeSuccessModal);
        if (backdrop) backdrop.addEventListener('click', closeSuccessModal);
    })();
</script>
@endpush
