<?php

namespace App\Http\Controllers;

use App\Models\ScreeningQuestion;
use App\Models\ScreeningAnswer;
use App\Models\ScreeningSession;
use App\Support\CkgScreeningCatalog;
use App\Support\TicketNumberGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class OperationalController extends Controller
{
    public function individu(Request $request): View
    {
        $query = ScreeningSession::query()->with('respondent');
        $keyword = trim((string)$request->input('ticket_number', ''));
        $filterType = (string)$request->input('filter_type', 'ticket');

        if ($request->filled('start_date')) {
            $query->whereDate('screened_at', '>=', $request->string('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('screened_at', '<=', $request->string('end_date'));
        }
        if ($keyword !== '') {
            if ($filterType === 'nik') {
                $query->whereHas('respondent', fn (Builder $q) => $q->where('nik', 'like', '%'.$keyword.'%'));
            } elseif ($filterType === 'name') {
                $query->whereHas('respondent', fn (Builder $q) => $q->where('name', 'like', '%'.$keyword.'%'));
            } else {
                $query->where('ticket_number', 'like', '%'.$keyword.'%');
            }
        }

        $sessions = $query->latest()->paginate(10)->withQueryString();

        return view('operational.individu', compact('sessions'));
    }

    public function pelayanan(Request $request): View
    {
        $query = ScreeningSession::query()->with('respondent')->withCount('screeningAnswers');
        $keyword = trim((string)$request->input('ticket_number', ''));
        $filterType = (string)$request->input('filter_type', 'ticket');

        if ($request->filled('start_date')) {
            $query->whereDate('screened_at', '>=', $request->string('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('screened_at', '<=', $request->string('end_date'));
        }
        if ($keyword !== '') {
            if ($filterType === 'nik') {
                $query->whereHas('respondent', fn (Builder $q) => $q->where('nik', 'like', '%'.$keyword.'%'));
            } elseif ($filterType === 'name') {
                $query->whereHas('respondent', fn (Builder $q) => $q->where('name', 'like', '%'.$keyword.'%'));
            } else {
                $query->where('ticket_number', 'like', '%'.$keyword.'%');
            }
        }

        $status = $request->string('status')->toString();
        if (in_array($status, ['belum_diperiksa', 'sedang_diperiksa', 'selesai_pemeriksaan'], true)) {
            $query->where('service_status', $status);
        }

        $sessions = $query->latest()->paginate(10)->withQueryString();

        $totalQuestions = ScreeningQuestion::query()->count();

        return view('operational.pelayanan', compact('sessions', 'status', 'totalQuestions'));
    }

    public function confirmAttendance(Request $request, ScreeningSession $session): RedirectResponse
    {
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'attendance_ack' => 'accepted',
        ]);

        $session->update([
            'attendance_status' => 'sudah_hadir',
            'screened_at' => $data['attendance_date'].' 00:00:00',
        ]);
        return back()
            ->with('success', 'Peserta berhasil dikonfirmasi hadir.')
            ->with('attendance_popup', [
                'ticket' => $session->ticket_number,
            ]);
    }

    public function changeDate(Request $request, ScreeningSession $session): RedirectResponse
    {
        $data = $request->validate([
            'ckg_date' => 'required|date',
        ]);

        $session->respondent()->update(['ckg_date' => $data['ckg_date']]);
        $session->update([
            'screened_at' => $data['ckg_date'].' 00:00:00',
            'ticket_number' => TicketNumberGenerator::make(),
        ]);

        return back()->with('success', 'Tanggal pemeriksaan berhasil diubah dan nomor tiket diperbarui.');
    }

    public function startService(ScreeningSession $session): RedirectResponse
    {
        if ($session->service_status === 'selesai_pemeriksaan') {
            return back()->withErrors(['service' => 'Layanan sudah selesai dan tidak dapat diubah.']);
        }
        if ($session->attendance_status !== 'sudah_hadir') {
            return back()->withErrors([
                'pelayanan' => 'Konfirmasi kehadiran peserta di menu Cari/Daftarkan Individu terlebih dahulu.',
            ]);
        }
        $session->update(['service_status' => 'sedang_diperiksa']);
        return redirect()
            ->route('pelayanan.detail', $session)
            ->with('success', 'Status pelayanan menjadi sedang diperiksa.');
    }

    public function finishService(ScreeningSession $session): RedirectResponse
    {
        if ($session->service_status === 'selesai_pemeriksaan') {
            return back()->withErrors(['service' => 'Layanan sudah selesai dan tidak dapat diubah.']);
        }
        $session->update(['service_status' => 'selesai_pemeriksaan']);
        return redirect()
            ->route('pelayanan.detail', $session)
            ->with('success', 'Pelayanan diselesaikan.');
    }

    public function sendReport(ScreeningSession $session): RedirectResponse
    {
        if ($session->service_status !== 'selesai_pemeriksaan') {
            return back()->withErrors(['rapor' => 'Rapor hanya dapat dikirim setelah pemeriksaan selesai.']);
        }
        $session->update(['report_sent_at' => now()]);
        $phone = $session->respondent?->phone ?? '-';
        return back()->with('success', 'Rapor ditandai terkirim ke WhatsApp peserta ('.$phone.').');
    }

    public function serviceDetail(Request $request, ScreeningSession $session): View
    {
        $session->load('respondent');
        $request->session()->put('ckg_wizard_respondent_id', (int) $session->respondent_id);
        $request->session()->put('ckg_wizard_screening_session_id', (int) $session->id);
        $services = $this->serviceChecklist($session->respondent?->gender);
        $categoryOrder = collect(CkgScreeningCatalog::categoryOrder())
            ->reject(function (array $cat) use ($session): bool {
                return ($session->respondent?->gender === 'male') && (($cat['code'] ?? '') === 'kanker_leher_rahim');
            })
            ->values()
            ->all();
        $categoryCodes = collect($categoryOrder)->pluck('code')->all();

        $questionCounts = ScreeningQuestion::query()
            ->selectRaw('category, COUNT(*) as total')
            ->whereIn('category', $categoryCodes)
            ->groupBy('category')
            ->pluck('total', 'category');

        $answeredCounts = ScreeningAnswer::query()
            ->selectRaw('screening_questions.category as category, COUNT(DISTINCT screening_answers.screening_question_id) as total')
            ->join('screening_questions', 'screening_questions.id', '=', 'screening_answers.screening_question_id')
            ->where('screening_answers.screening_session_id', $session->id)
            ->whereIn('screening_questions.category', $categoryCodes)
            ->groupBy('screening_questions.category')
            ->pluck('total', 'category');

        $respondentGender = $session->respondent?->gender;
        $mandiriRows = collect($categoryOrder)->map(function (array $cat) use ($questionCounts, $answeredCounts, $respondentGender): array {
            $total = (int)($questionCounts[$cat['code']] ?? 0);
            $answered = (int)($answeredCounts[$cat['code']] ?? 0);
            $title = $cat['title'];
            if (($cat['code'] ?? '') === 'demografi_dewasa') {
                $title = 'Demografi Dewasa '.($respondentGender === 'male' ? 'Laki-laki' : 'Perempuan');
            }

            return [
                'title' => $title,
                'slug' => $cat['slug'],
                'total' => $total,
                'answered' => $answered,
                'complete' => $total > 0 && $answered >= $total,
            ];
        })->all();

        return view('operational.service_detail', compact('session', 'services', 'mandiriRows'));
    }

    public function saveServiceDetail(Request $request, ScreeningSession $session): RedirectResponse
    {
        if ($session->service_status === 'selesai_pemeriksaan') {
            return back()->withErrors(['service' => 'Layanan sudah selesai dan tidak dapat diubah.']);
        }

        $data = $request->validate([
            'notes' => 'array',
            'notes.*.status' => 'nullable|in:belum,sedang,selesai',
            'notes.*.catatan' => 'nullable|string|max:2000',
            'notes.*.details_json' => 'nullable|string|max:12000',
            'notes.*.enabled' => 'nullable|in:0,1',
            'notes.*.sections' => 'array',
            'notes.*.sections.*.status' => 'nullable|in:belum,sedang,selesai',
            'notes.*.sections.*.enabled' => 'nullable|in:0,1',
        ]);

        $notes = $data['notes'] ?? [];
        foreach ($notes as $key => $row) {
            $json = (string)($row['details_json'] ?? '');
            if ($json === '') {
                continue;
            }

            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                $notes[$key]['details'] = $decoded;
            }
        }

        $session->update(['service_notes' => $notes]);
        return back()->with('success', 'Detail pelayanan berhasil disimpan.');
    }

    public function updateDetailData(Request $request, ScreeningSession $session): RedirectResponse
    {
        $data = $request->validate([
            'phone' => 'required|string|max:32',
            'work_unit' => 'required|string|max:255',
            'address' => 'required|string|max:2000',
        ]);

        $session->respondent()->update($data);

        return redirect()
            ->route('pelayanan.detail', $session)
            ->with('success', 'Data individu berhasil diperbarui.');
    }

    public function nakesInputForm(Request $request, ScreeningSession $session, string $serviceKey): View|RedirectResponse
    {
        $services = $this->serviceChecklist($session->respondent?->gender);
        if (!array_key_exists($serviceKey, $services)) {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Jenis layanan tidak ditemukan.',
            ]);
        }
        if ($serviceKey === 'catin_laki' && $session->respondent?->gender !== 'male') {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Layanan ini hanya untuk peserta laki-laki.',
            ]);
        }
        if ($serviceKey === 'kanker_payudara' && $session->respondent?->gender !== 'female') {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Layanan ini hanya untuk peserta perempuan.',
            ]);
        }
        if ($serviceKey === 'kanker_leher_rahim' && $session->respondent?->gender !== 'female') {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Layanan ini hanya untuk peserta perempuan.',
            ]);
        }
        if ($serviceKey === 'catin_perempuan' && $session->respondent?->gender !== 'female') {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Layanan ini hanya untuk peserta perempuan.',
            ]);
        }

        $notes = $session->service_notes ?? [];
        $item = $notes[$serviceKey] ?? [];
        $templates = $this->nakesInputTemplates($session->respondent?->gender);
        $section = (string)$request->query('section', '');
        $sectionOptions = $this->giziSectionOptions($session->respondent?->gender);
        $sectionLabel = '';

        if ($serviceKey === 'skrining_gizi') {
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'antropometri';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = ($notes['skrining_gizi']['sections'][$section] ?? []);
        } elseif ($serviceKey === 'skrining_gigi') {
            $sectionOptions = $this->gigiSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'karies_gigi_hilang';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = ($notes['skrining_gigi']['sections'][$section] ?? []);
        } elseif ($serviceKey === 'tuberkulosis') {
            $sectionOptions = $this->tbSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'faktor_xray';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = ($notes['tuberkulosis']['sections'][$section] ?? []);
        } elseif ($serviceKey === 'penyakit_tropis') {
            $sectionOptions = $this->tropisSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'frambusia';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = ($notes['penyakit_tropis']['sections'][$section] ?? []);
        } elseif ($serviceKey === 'ppok') {
            $sectionOptions = $this->ppokSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'puma';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['ppok'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'kadar_co') {
            $sectionOptions = $this->kadarCoSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'pernapasan';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['kadar_co'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'laboratorium') {
            $sectionOptions = $this->laboratoriumSectionOptions($session->respondent?->gender);
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'poct_lipid';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['laboratorium'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'jantung') {
            $sectionOptions = $this->jantungSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'hasil_ekg';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['jantung'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'kanker_usus') {
            $sectionOptions = $this->kankerUsusSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'lanjutan';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['kanker_usus'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'kanker_paru') {
            $sectionOptions = $this->kankerParuSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'usia_45';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['kanker_paru'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'catin_laki') {
            $sectionOptions = $this->catinLakiSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'hiv';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['catin_laki'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'kanker_payudara') {
            $sectionOptions = $this->kankerPayudaraSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'sadanis';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['kanker_payudara'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'kanker_leher_rahim') {
            $sectionOptions = $this->kankerLeherRahimSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'hpv_dna';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['kanker_leher_rahim'] ?? [])['sections'] ?? [])[$section] ?? [];
        } elseif ($serviceKey === 'catin_perempuan') {
            $sectionOptions = $this->catinPerempuanSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                $section = array_key_first($sectionOptions) ?: 'cp_perempuan';
            }
            $sectionLabel = $sectionOptions[$section] ?? '';
            $item = (($notes['catin_perempuan'] ?? [])['sections'] ?? [])[$section] ?? [];
        }

        return view('operational.nakes_input', [
            'session' => $session,
            'serviceKey' => $serviceKey,
            'serviceLabel' => $services[$serviceKey],
            'item' => $item,
            'fields' => $templates[$serviceKey] ?? $templates['_default'],
            'section' => $section,
            'sectionLabel' => $sectionLabel,
        ]);
    }

    public function saveNakesInputForm(Request $request, ScreeningSession $session, string $serviceKey): RedirectResponse
    {
        if ($session->service_status === 'selesai_pemeriksaan') {
            return back()->withErrors(['service' => 'Layanan sudah selesai dan tidak dapat diubah.']);
        }

        $services = $this->serviceChecklist($session->respondent?->gender);
        if (!array_key_exists($serviceKey, $services)) {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Jenis layanan tidak ditemukan.',
            ]);
        }
        if ($serviceKey === 'catin_laki' && $session->respondent?->gender !== 'male') {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Layanan ini hanya untuk peserta laki-laki.',
            ]);
        }
        if ($serviceKey === 'kanker_payudara' && $session->respondent?->gender !== 'female') {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Layanan ini hanya untuk peserta perempuan.',
            ]);
        }
        if ($serviceKey === 'kanker_leher_rahim' && $session->respondent?->gender !== 'female') {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Layanan ini hanya untuk peserta perempuan.',
            ]);
        }
        if ($serviceKey === 'catin_perempuan' && $session->respondent?->gender !== 'female') {
            return redirect()->route('pelayanan.detail', $session)->withErrors([
                'service' => 'Layanan ini hanya untuk peserta perempuan.',
            ]);
        }

        $data = $request->validate([
            'status' => 'required|in:belum,sedang,selesai',
            'catatan' => 'nullable|string|max:2000',
            'details' => 'array',
            'details.*' => 'nullable|string|max:1000',
        ]);

        $notes = $session->service_notes ?? [];
        $section = (string)$request->input('section', '');

        if ($serviceKey === 'ppok' && $section === 'puma') {
            $request->validate([
                'details.ppok_riwayat_merokok' => 'required|in:iya,tidak',
                'details.ppok_napas_pendek' => 'required|in:ya,tidak',
                'details.ppok_dahak' => 'required|in:ya,tidak',
                'details.ppok_batuk' => 'required|in:ya,tidak',
                'details.ppok_spirometri' => 'required|in:ya,tidak',
            ]);
            if (($request->input('details.ppok_riwayat_merokok') ?? '') === 'iya') {
                $request->validate([
                    'details.ppok_bungkus_tahun' => 'required|in:lt_10,10_20,20_30,gt_30',
                ]);
            }
            $details = $data['details'] ?? [];
            if (($details['ppok_riwayat_merokok'] ?? '') === 'tidak') {
                $details['ppok_bungkus_tahun'] = 'tidak_berlaku';
            }
            $data['details'] = $details;
        }

        if ($serviceKey === 'kadar_co' && $section === 'pernapasan') {
            $request->validate([
                'details.co_ppm' => 'required|numeric|min:0|max:9999',
            ]);
        }

        if ($serviceKey === 'laboratorium') {
            $labOpts = $this->laboratoriumSectionOptions($session->respondent?->gender);
            if (!array_key_exists($section, $labOpts)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining laboratorium tidak valid.',
                ]);
            }
            $this->validateLaboratoriumInput($request, $section);
        }

        if ($serviceKey === 'jantung') {
            $jOpts = $this->jantungSectionOptions();
            if (!array_key_exists($section, $jOpts)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining jantung tidak valid.',
                ]);
            }
            if ($section === 'hasil_ekg') {
                $request->validate([
                    'details.jtg_hasil_pemeriksaan_ekg' => 'required|in:normal,abnormal',
                    'details.jtg_pemeriksaan_ekg' => 'required|in:normal,st_depresi,t_inversi,hipertrofi_vki,atrial_fibrilasi,q_patologis,st_elevasi,gambaran_lainnya',
                ]);
            }
        }

        if ($serviceKey === 'kanker_usus') {
            $kuOpts = $this->kankerUsusSectionOptions();
            if (!array_key_exists($section, $kuOpts)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining kanker usus tidak valid.',
                ]);
            }
            if ($section === 'lanjutan') {
                $request->validate([
                    'details.ku_kesediaan_colok' => 'required|in:bersedia,menolak,tidak_tahu',
                ]);
                if (($request->input('details.ku_kesediaan_colok') ?? '') === 'bersedia') {
                    $request->validate([
                        'details.ku_colok_dubur' => 'required|in:ditemukan_benjolan,tidak_ditemukan_benjolan',
                        'details.ku_darah_samar' => 'required|in:negatif,positif',
                    ]);
                }
                $details = $data['details'] ?? [];
                if (($details['ku_kesediaan_colok'] ?? '') !== 'bersedia') {
                    $details['ku_colok_dubur'] = 'tidak_berlaku';
                    $details['ku_darah_samar'] = 'tidak_berlaku';
                }
                $data['details'] = $details;
            }
        }

        if ($serviceKey === 'kanker_paru') {
            $kpOpts = $this->kankerParuSectionOptions();
            if (!array_key_exists($section, $kpOpts)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining kanker paru tidak valid.',
                ]);
            }
            if ($section === 'usia_45') {
                $kpDetails = $request->input('details', []);
                if (($kpDetails['kp_q2_keluarga'] ?? '') === '') {
                    $kpDetails['kp_q2_keluarga'] = null;
                    $request->merge(['details' => $kpDetails]);
                }
                $request->validate([
                    'details.kp_q1_diagnosis' => 'required|in:dx_gt5,dx_lt5,tidak_pernah',
                    'details.kp_q2_keluarga' => 'nullable|in:kel_paru,kel_lain,kel_tidak',
                    'details.kp_q3_rokok' => 'required|in:rokok_aktif,rokok_berhenti,rokok_pasif,rokok_tidak',
                    'details.kp_q4_karsinogen' => 'required|in:kars_ya,kars_tidak_yakin,kars_tidak',
                    'details.kp_q5_lingkungan' => 'required|in:tinggi_ya,tinggi_tidak_yakin,tinggi_tidak',
                    'details.kp_q6_rumah' => 'required|in:rumah_tidak_sehat,rumah_tidak_yakin,rumah_sehat',
                    'details.kp_q7_paru_kronik' => 'required|in:kronik_tbc,kronik_lain,kronik_tidak',
                    'details.kp_q8_foto_torax' => 'required|in:toraks_normal,toraks_tidak_normal',
                ]);
                $data['details'] = $request->input('details', []);
            }
        }

        if ($serviceKey === 'catin_laki') {
            $clOpts = $this->catinLakiSectionOptions();
            if (!array_key_exists($section, $clOpts)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian pemeriksaan calon pengantin tidak valid.',
                ]);
            }
            if ($section === 'hiv') {
                $request->validate([
                    'details.cl_hiv_rapid' => 'required|in:reaktif,non_reaktif',
                ]);
                $data['details'] = $request->input('details', []);
            } elseif ($section === 'sifilis') {
                $request->validate([
                    'details.cl_sifilis_rapid' => 'required|in:reaktif,non_reaktif',
                ]);
                $data['details'] = $request->input('details', []);
            }
        }

        if ($serviceKey === 'kanker_payudara') {
            $kpdOpts = $this->kankerPayudaraSectionOptions();
            if (!array_key_exists($section, $kpdOpts)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining kanker payudara tidak valid.',
                ]);
            }
            if ($section === 'sadanis') {
                $request->validate([
                    'details.kpd_tindakan' => 'required|in:sadanis',
                    'details.kpd_hasil_sadanis' => 'required|in:normal,tidak_normal',
                ]);
                $data['details'] = $request->input('details', []);
            }
        }

        if ($serviceKey === 'kanker_leher_rahim') {
            $klrOpts = $this->kankerLeherRahimSectionOptions();
            if (!array_key_exists($section, $klrOpts)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining kanker leher rahim tidak valid.',
                ]);
            }
            if ($section === 'hpv_dna') {
                $request->validate([
                    'details.klr_hpv_dna' => 'required|in:negatif,positif',
                ]);
                $data['details'] = $request->input('details', []);
            } elseif ($section === 'inspekulo_iva') {
                $request->validate([
                    'details.klr_inspekulo' => 'required|in:normal,curiga_kanker',
                    'details.klr_iva' => 'required|in:negatif,positif',
                ]);
                $data['details'] = $request->input('details', []);
            }
        }

        if ($serviceKey === 'catin_perempuan') {
            $cpOpts = $this->catinPerempuanSectionOptions();
            if (!array_key_exists($section, $cpOpts)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian pemeriksaan calon pengantin perempuan tidak valid.',
                ]);
            }
            if ($section === 'cp_perempuan') {
                $request->validate([
                    'details.cp_hb' => 'required|in:hb_normal,hb_rendah',
                ]);
                $data['details'] = $request->input('details', []);
            } elseif ($section === 'hiv') {
                $request->validate([
                    'details.cp_hiv_rapid' => 'required|in:reaktif,non_reaktif',
                ]);
                $data['details'] = $request->input('details', []);
            } elseif ($section === 'sifilis') {
                $request->validate([
                    'details.cp_sifilis_rapid' => 'required|in:reaktif,non_reaktif',
                ]);
                $data['details'] = $request->input('details', []);
            }
        }

        if ($serviceKey === 'skrining_gizi') {
            $sectionOptions = $this->giziSectionOptions($session->respondent?->gender);
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining gizi tidak valid.',
                ]);
            }

            $notes['skrining_gizi']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'skrining_gigi') {
            $sectionOptions = $this->gigiSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining gigi tidak valid.',
                ]);
            }

            $notes['skrining_gigi']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'tuberkulosis') {
            $sectionOptions = $this->tbSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian tuberkulosis tidak valid.',
                ]);
            }

            $notes['tuberkulosis']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'penyakit_tropis') {
            $sectionOptions = $this->tropisSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian penyakit tropis tidak valid.',
                ]);
            }

            $notes['penyakit_tropis']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'ppok') {
            $sectionOptions = $this->ppokSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian PPOK tidak valid.',
                ]);
            }

            $notes['ppok']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'kadar_co') {
            $sectionOptions = $this->kadarCoSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian pemeriksaan kadar CO tidak valid.',
                ]);
            }

            $notes['kadar_co']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'laboratorium') {
            $sectionOptions = $this->laboratoriumSectionOptions($session->respondent?->gender);
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining laboratorium tidak valid.',
                ]);
            }

            $notes['laboratorium']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'jantung') {
            $sectionOptions = $this->jantungSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining jantung tidak valid.',
                ]);
            }

            $notes['jantung']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'kanker_usus') {
            $sectionOptions = $this->kankerUsusSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining kanker usus tidak valid.',
                ]);
            }

            $notes['kanker_usus']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'kanker_paru') {
            $sectionOptions = $this->kankerParuSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining kanker paru tidak valid.',
                ]);
            }

            $notes['kanker_paru']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'catin_laki') {
            $sectionOptions = $this->catinLakiSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian pemeriksaan calon pengantin tidak valid.',
                ]);
            }

            $notes['catin_laki']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'kanker_payudara') {
            $sectionOptions = $this->kankerPayudaraSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining kanker payudara tidak valid.',
                ]);
            }

            $notes['kanker_payudara']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'kanker_leher_rahim') {
            $sectionOptions = $this->kankerLeherRahimSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian skrining kanker leher rahim tidak valid.',
                ]);
            }

            $notes['kanker_leher_rahim']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } elseif ($serviceKey === 'catin_perempuan') {
            $sectionOptions = $this->catinPerempuanSectionOptions();
            if (!array_key_exists($section, $sectionOptions)) {
                return redirect()->route('pelayanan.detail', $session)->withErrors([
                    'service' => 'Bagian pemeriksaan calon pengantin perempuan tidak valid.',
                ]);
            }

            $notes['catin_perempuan']['sections'][$section] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        } else {
            $notes[$serviceKey] = [
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? '',
                'details' => $data['details'] ?? [],
                'enabled' => true,
            ];
        }

        $session->update(['service_notes' => $notes]);

        return redirect()
            ->route('pelayanan.detail', $session)
            ->with('success', 'Input data layanan '.$services[$serviceKey].' berhasil disimpan.');
    }

    public function report(ScreeningSession $session): View|RedirectResponse
    {
        if ($session->service_status !== 'selesai_pemeriksaan') {
            return redirect()
                ->route('pelayanan.index')
                ->withErrors(['rapor' => 'Rapor dapat dibuka setelah status pemeriksaan selesai.']);
        }
        $session->load([
            'respondent',
            'screeningAnswers.screeningQuestion',
            'screeningAnswers.screeningOption',
        ]);

        return view('operational.report', ['session' => $session]);
    }

    public function reportPdf(ScreeningSession $session): Response|RedirectResponse
    {
        if ($session->service_status !== 'selesai_pemeriksaan') {
            return redirect()
                ->route('pelayanan.index')
                ->withErrors(['rapor' => 'Unduh PDF rapor tersedia setelah pemeriksaan selesai.']);
        }
        $session->load([
            'respondent',
            'screeningAnswers.screeningQuestion',
            'screeningAnswers.screeningOption',
        ]);

        $pdf = Pdf::loadView('operational.report_pdf', ['session' => $session]);
        return $pdf->download('rapor-'.$session->ticket_number.'.pdf');
    }

    private function serviceChecklist(?string $gender): array
    {
        $laboratoriumGenderLabel = $gender === 'male' ? 'Laki-laki' : 'Perempuan';
        $base = [
            'skrining_gizi' => 'Skrining gizi',
            'telinga_mata' => 'Skrining telinga dan mata',
            'skrining_gigi' => 'Skrining gigi',
            'tuberkulosis' => 'Tuberkulosis',
            'penyakit_tropis' => 'Layanan penyakit tropis terabaikan',
            'ppok' => 'Pemeriksaan PPOK (Skrining PUMA)',
            'kadar_co' => 'Pemeriksaan Kadar CO (Tatalaksana Merokok)',
            'laboratorium' => 'Skrining Laboratorium = > 40 thn '.$laboratoriumGenderLabel.' Gula Darah, Fungsi Ginjal, Hati, Profil Lipid',
            'jantung' => 'Skrining Jantung (Pemeriksaan EKG - hanya penyandang HT & DM)',
            'kanker_usus' => 'Skrining Kanker Usus (TL APCS)',
            'kanker_paru' => 'Skrining Kanker Paru (Usia = > 45 tahun)',
        ];

        if ($gender === 'male') {
            $base['catin_laki'] = 'Pemeriksaan Calon Pengantin Laki-laki';
            return $base;
        }

        $base['kanker_payudara'] = 'Skrining Kanker Payudara';
        $base['kanker_leher_rahim'] = 'Skrining Kanker Leher Rahim';
        $base['catin_perempuan'] = 'Pemeriksaan Calon Pengantin Perempuan';
        return $base;
    }

    private function nakesInputTemplates(?string $gender): array
    {
        return [
            'skrining_gizi' => ['Nilai pemeriksaan', 'Interpretasi', 'Temuan tambahan', 'Intervensi', 'Rencana tindak lanjut'],
            'telinga_mata' => ['Visus mata kanan', 'Visus mata kiri', 'Hasil pemeriksaan telinga', 'Keluhan utama', 'Rencana tindak lanjut'],
            'skrining_gigi' => ['Kondisi gigi', 'Kondisi gusi', 'Karies', 'Keluhan nyeri', 'Rujukan dokter gigi'],
            'tuberkulosis' => ['Skrining gejala TB', 'Riwayat kontak TB', 'Pemeriksaan dahak', 'Hasil foto toraks', 'Rencana tindak lanjut'],
            'penyakit_tropis' => ['Riwayat gejala', 'Pemeriksaan fisik', 'Faktor risiko', 'Diagnosa kerja', 'Tindak lanjut'],
            'ppok' => ['Keluhan pernapasan', 'Riwayat merokok', 'Hasil pemeriksaan', 'Klasifikasi risiko', 'Rencana terapi'],
            'kadar_co' => ['Nilai kadar CO', 'Kategori hasil', 'Durasi paparan', 'Edukasi pasien', 'Tindak lanjut'],
            'laboratorium' => ['Hemoglobin', 'Gula darah sewaktu', 'Kolesterol', 'Asam urat', 'Catatan hasil lab'],
            'jantung' => ['Tekanan darah', 'Nadi', 'Keluhan dada', 'Interpretasi EKG/skrining', 'Rujukan'],
            'kanker_usus' => ['Keluhan pencernaan', 'Faktor risiko', 'Skrining awal', 'Temuan klinis', 'Rujukan'],
            'kanker_paru' => ['Gejala respirasi', 'Faktor risiko', 'Skrining awal', 'Temuan klinis', 'Rujukan'],
            'catin_laki' => ['Status calon pengantin', 'Pemeriksaan umum', 'Konseling', 'Temuan', 'Rencana lanjut'],
            'kanker_payudara' => ['Keluhan payudara', 'Pemeriksaan SADANIS', 'Temuan benjolan', 'Klasifikasi', 'Rujukan'],
            'kanker_leher_rahim' => ['Riwayat IVA/Pap smear', 'Hasil pemeriksaan', 'Temuan lesi', 'Klasifikasi', 'Rujukan'],
            'catin_perempuan' => ['Status calon pengantin', 'Pemeriksaan umum', 'Konseling', 'Temuan', 'Rencana lanjut'],
            '_default' => ['Hasil pemeriksaan', 'Temuan klinis', 'Diagnosa/kesimpulan', 'Intervensi', 'Rencana tindak lanjut'],
        ];
    }

    private function giziSectionOptions(?string $gender): array
    {
        return [
            'antropometri' => 'Gizi (BB - TB - Lingkar Perut) '.($gender === 'male' ? 'Laki-laki' : 'Perempuan'),
            'gds' => 'Pemeriksaan Gula Darah Dewasa Lansia',
            'td' => 'Tekanan Darah Dewasa Lansia',
        ];
    }

    private function gigiSectionOptions(): array
    {
        return [
            'karies_gigi_hilang' => 'Skrining Karies dan Gigi Hilang',
            'penyakit_periodontal' => 'Skrining Penyakit Periodontal',
        ];
    }

    private function tbSectionOptions(): array
    {
        return [
            'faktor_xray' => 'Faktor Risiko dan Skrining X-Ray TB (Dewasa & Lansia)',
            'pemeriksaan_tb' => 'Pemeriksaan Tuberkulosis (Dewasa & Lansia)',
        ];
    }

    private function tropisSectionOptions(): array
    {
        return [
            'frambusia' => 'Pemeriksaan Penyakit Frambusia (untuk daerah endemis atau berisiko frambusia)',
            'kusta' => 'Pemeriksaan Penyakit Kusta',
            'skabies' => 'Pemeriksaan Penyakit Skabies',
        ];
    }

    private function ppokSectionOptions(): array
    {
        return [
            'puma' => 'Pemeriksaan PPOK (Skrining PUMA)',
        ];
    }

    private function kadarCoSectionOptions(): array
    {
        return [
            'pernapasan' => 'Pemeriksaan Kadar CO (Hanya Diisi Apabila Merokok atau Terpapar Asap Rokok)',
        ];
    }

    private function jantungSectionOptions(): array
    {
        return [
            'hasil_ekg' => 'Hasil Pemeriksaan - Skrining Jantung',
        ];
    }

    private function kankerUsusSectionOptions(): array
    {
        return [
            'lanjutan' => 'Pemeriksaan Lanjutan Kanker Usus',
        ];
    }

    private function kankerParuSectionOptions(): array
    {
        return [
            'usia_45' => 'Skrining Kanker Paru (Usia = > 45 thn)',
        ];
    }

    private function catinLakiSectionOptions(): array
    {
        return [
            'hiv' => 'Pemeriksaan HIV',
            'sifilis' => 'Pemeriksaan Sifilis',
        ];
    }

    private function kankerPayudaraSectionOptions(): array
    {
        return [
            'sadanis' => 'Skrining Kanker Payudara',
        ];
    }

    private function kankerLeherRahimSectionOptions(): array
    {
        return [
            'hpv_dna' => 'Hasil Pemeriksaan HPV-DNA',
            'inspekulo_iva' => 'Pemeriksaan Inspekulo dan IVA',
        ];
    }

    private function catinPerempuanSectionOptions(): array
    {
        return [
            'cp_perempuan' => 'Pemeriksaan Calon Pengantin Perempuan',
            'hiv' => 'Pemeriksaan HIV',
            'sifilis' => 'Pemeriksaan Sifilis',
        ];
    }

    private function laboratoriumSectionOptions(?string $gender): array
    {
        $genderLabel = $gender === 'male' ? 'Laki-Laki' : 'Perempuan';

        return [
            'poct_lipid' => 'POCT Lipid Panel (Khusus usia >= 40 thn dan penyandang HT dan/atau DM)',
            'fibrosis_hati' => 'Pemeriksaan Fibrosis/Sirosis Hati',
            'hepatitis' => 'Pemeriksaan Hepatitis',
            'fungsi_ginjal_lk' => 'Skrining Fungsi Ginjal '.$genderLabel.' (hanya untuk = > 40 tahun dengan risiko HT DM)',
            'kerusakan_ginjal' => 'Skrining Kerusakan Ginjal (hanya untuk = > 40 tahun dengan risiko HT DM)',
        ];
    }

    private function validateLaboratoriumInput(Request $request, string $section): void
    {
        switch ($section) {
            case 'poct_lipid':
                $request->validate([
                    'details.lab_kolesterol_total' => 'required|numeric',
                    'details.lab_hdl' => 'required|numeric',
                    'details.lab_ldl' => 'nullable|numeric',
                    'details.lab_trigliserida' => 'nullable|numeric',
                ]);
                break;
            case 'fibrosis_hati':
                $request->validate([
                    'details.lab_sgot' => 'required|numeric',
                    'details.lab_trombosit' => 'required|numeric',
                ]);
                break;
            case 'hepatitis':
                $request->validate([
                    'details.lab_hepatitis_b' => 'required|in:hbsag_non_reaktif,hbsag_reaktif',
                    'details.lab_hepatitis_c' => 'required|in:anti_hcv_non_reaktif,anti_hcv_reaktif',
                ]);
                break;
            case 'fungsi_ginjal_lk':
                $request->validate([
                    'details.lab_kreatinin' => 'required|numeric',
                    'details.lab_ureum' => 'required|numeric',
                    'details.lab_usia_scr_ginjal' => 'required|numeric',
                    'details.lab_elfg_ckd_epi' => 'required|numeric',
                ]);
                break;
            case 'kerusakan_ginjal':
                $request->validate([
                    'details.lab_albumin_urin' => 'required|numeric',
                    'details.lab_kreatinin_urin' => 'required|numeric',
                ]);
                break;
        }
    }
}
