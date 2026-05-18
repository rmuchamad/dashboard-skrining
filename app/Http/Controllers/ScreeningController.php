<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use App\Models\ScreeningAnswer;
use App\Models\ScreeningOption;
use App\Models\ScreeningQuestion;
use App\Models\ScreeningSession;
use App\Support\CkgScreeningCatalog;
use App\Support\TicketNumberGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class ScreeningController extends Controller
{
    private const SESSION_RESPONDENT = 'ckg_wizard_respondent_id';

    private const SESSION_SCREENING = 'ckg_wizard_screening_session_id';

    public function create()
    {
        return view('screening.create');
    }

    public function storeStep1(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:32',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'work_unit' => 'required|string|max:255',
            'participant_category' => 'required|in:pjlp,non_asn,asn',
            'skpd' => 'nullable|string|max:255',
            'ukpd' => 'nullable|string|max:255',
            'phone' => 'required|string|max:32',
            'guardian_phone' => 'nullable|string|max:32',
            'guardian_name' => 'nullable|string|max:255',
            'province' => 'required|string|max:255',
            'province_code' => 'nullable|string|max:20',
            'regency' => 'required|string|max:255',
            'regency_code' => 'nullable|string|max:20',
            'district' => 'required|string|max:255',
            'district_code' => 'nullable|string|max:20',
            'village' => 'required|string|max:255',
            'village_code' => 'nullable|string|max:20',
            'address' => 'required|string|max:2000',
            'clinic_name' => 'required|string|max:255',
            'ckg_location' => 'required|string|max:255',
            'ckg_date' => 'required|date',
            'update_session_id' => 'nullable|integer|exists:screening_sessions,id',
            '_ps' => 'nullable|in:1,2',
        ]);

        $updateSessionId = isset($data['update_session_id']) ? (int)$data['update_session_id'] : null;
        unset($data['_ps'], $data['update_session_id']);

        $age = Carbon::parse($data['birth_date'])->age;

        $respondent = Respondent::query()->updateOrCreate(
            ['nik' => $data['nik']],
            [
                ...$data,
                'age' => $age,
            ]
        );

        $screenedAt = Carbon::parse($data['ckg_date'])->startOfDay();

        if ($updateSessionId) {
            $session = ScreeningSession::query()->with('respondent')->findOrFail($updateSessionId);
            $session->update([
                'respondent_id' => $respondent->id,
                'ticket_number' => TicketNumberGenerator::make(),
                'screened_at' => $screenedAt,
            ]);

            $request->session()->forget([self::SESSION_RESPONDENT, self::SESSION_SCREENING]);

            return redirect()
                ->route('individu.index')
                ->with('success', 'Data peserta dan tanggal pemeriksaan berhasil diperbarui.')
                ->with('ticket_popup', $session->ticket_number);
        }

        $session = ScreeningSession::create([
            'respondent_id' => $respondent->id,
            'ticket_number' => TicketNumberGenerator::make(),
            'screened_at' => $screenedAt,
            'risk_score' => 0,
            'risk_category' => 'Rendah',
        ]);

        $request->session()->forget([self::SESSION_RESPONDENT, self::SESSION_SCREENING]);

        return redirect()
            ->route('individu.index')
            ->with('success', 'Pendaftaran berhasil.')
            ->with('ticket_popup', $session->ticket_number);
    }

    public function wizard(Request $request, string $kategori)
    {
        $categoryCode = CkgScreeningCatalog::slugToCode($kategori);
        abort_if($categoryCode === null, 404);

        $respondentId = (int)$request->session()->get(self::SESSION_RESPONDENT);
        $sessionId = (int)$request->session()->get(self::SESSION_SCREENING);
        abort_if($respondentId <= 0 || $sessionId <= 0, 403);

        $questions = ScreeningQuestion::query()
            ->where('category', $categoryCode)
            ->with('screeningOptions')
            ->orderBy('order')
            ->get();
        $respondent = Respondent::query()->findOrFail($respondentId);
        $questions = $this->filterQuestionsForRespondent($questions, $respondent, $categoryCode);

        $prefill = [];
        foreach ($questions as $question) {
            $answer = ScreeningAnswer::query()
                ->where('screening_session_id', $sessionId)
                ->where('screening_question_id', $question->id)
                ->first();

            if (!$answer) {
                continue;
            }

            if ($question->type === 'single_choice') {
                $prefill[$question->code] = $answer->screening_option_id;
            } else {
                $prefill[$question->code] = $answer->text_answer;
            }
        }

        $meta = collect(CkgScreeningCatalog::categoryOrder())->keyBy('slug');
        $title = $meta[$kategori]['title'] ?? 'Kuesioner';
        if ($categoryCode === 'demografi_dewasa') {
            $title = 'Demografi Dewasa '.($respondent->gender === 'male' ? 'Laki-laki' : 'Perempuan');
        }

        $backToMainUrl = route('individu.index');
        if ($sessionId > 0) {
            $backToMainUrl = route('pelayanan.detail', ['session' => $sessionId]);
        }

        return view('screening.wizard', [
            'kategori' => $kategori,
            'categoryCode' => $categoryCode,
            'title' => $title,
            'questions' => $questions,
            'prefill' => $prefill,
            'backToMainUrl' => $backToMainUrl,
        ]);
    }

    public function storeWizard(Request $request, string $kategori)
    {
        $categoryCode = CkgScreeningCatalog::slugToCode($kategori);
        abort_if($categoryCode === null, 404);

        $respondentId = (int)$request->session()->get(self::SESSION_RESPONDENT);
        $sessionId = (int)$request->session()->get(self::SESSION_SCREENING);
        abort_if($respondentId <= 0 || $sessionId <= 0, 403);

        $session = ScreeningSession::query()->findOrFail($sessionId);
        abort_if((int)$session->respondent_id !== $respondentId, 403);
        $respondent = Respondent::query()->findOrFail($respondentId);

        $questions = ScreeningQuestion::query()
            ->where('category', $categoryCode)
            ->with('screeningOptions')
            ->orderBy('order')
            ->get();
        $questions = $this->filterQuestionsForRespondent($questions, $respondent, $categoryCode);

        $this->validateAnswersForCategory($request, $questions, $categoryCode);

        DB::transaction(function () use ($request, $session, $questions): void {
            $answers = $request->input('answers', []);
            foreach ($questions as $question) {
                $code = $question->code;
                if (!array_key_exists($code, $answers)) {
                    continue;
                }
                $raw = $answers[$code];

                if ($question->type === 'single_choice') {
                    $optionId = (int)$raw;
                    $option = ScreeningOption::query()
                        ->where('id', $optionId)
                        ->where('screening_question_id', $question->id)
                        ->firstOrFail();

                    ScreeningAnswer::updateOrCreate(
                        [
                            'screening_session_id' => $session->id,
                            'screening_question_id' => $question->id,
                        ],
                        [
                            'screening_option_id' => $option->id,
                            'text_answer' => null,
                            'score' => (int)$option->score,
                        ]
                    );
                } else {
                    ScreeningAnswer::updateOrCreate(
                        [
                            'screening_session_id' => $session->id,
                            'screening_question_id' => $question->id,
                        ],
                        [
                            'screening_option_id' => null,
                            'text_answer' => (string)$raw,
                            'score' => 0,
                        ]
                    );
                }
            }
        });

        $this->finalizeScreeningSession($session);

        return redirect()
            ->route('pelayanan.detail', ['session' => $session->id])
            ->with('success', 'Skrining mandiri berhasil disimpan.');
    }

    public function checkNik(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nik' => 'required|string|max:32',
        ]);

        $respondent = Respondent::query()->where('nik', $data['nik'])->first();
        if (!$respondent) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => true,
            'respondent' => $respondent,
        ]);
    }

    public function searchCandidates(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
        ]);

        $q = Respondent::query()
            ->select(['id', 'nik', 'name', 'birth_date', 'gender'])
            ->whereDate('birth_date', $data['birth_date'])
            ->where('gender', $data['gender'])
            ->where('name', 'like', '%'.$data['name'].'%')
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->map(function (Respondent $respondent): array {
                return [
                    'id' => $respondent->id,
                    'nik' => (string)$respondent->nik,
                    'name' => (string)$respondent->name,
                    'birth_date' => optional($respondent->birth_date)->format('Y-m-d'),
                    'gender' => (string)$respondent->gender,
                ];
            })
            ->values();

        return response()->json([
            'items' => $q,
        ]);
    }

    private function finalizeScreeningSession(ScreeningSession $session): void
    {
        $totalScore = (int)ScreeningAnswer::query()
            ->where('screening_session_id', $session->id)
            ->sum('score');

        // 0–2 rendah, 3–4 sedang, ≥5 tinggi (skor berasal dari opsi kuesioner).
        $category = 'Rendah';
        if ($totalScore <= 2) {
            $category = 'Rendah';
        } elseif ($totalScore <= 4) {
            $category = 'Sedang';
        } else {
            $category = 'Tinggi';
        }

        $session->update([
            'risk_score' => $totalScore,
            'risk_category' => $category,
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection<int, ScreeningQuestion> $questions
     */
    private function validateAnswersForCategory(Request $request, $questions, string $categoryCode): void
    {
        $answers = $request->input('answers', []);
        $rules = [];

        $isSmokerLastYear = null;
        if ($categoryCode === 'perilaku_merokok') {
            $lastYearQuestion = $questions->firstWhere('code', 'smk_last_year');
            $lastYearAnswer = $answers[$lastYearQuestion?->code ?? ''] ?? null;
            if ($lastYearQuestion && $lastYearAnswer) {
                $opt = ScreeningOption::query()
                    ->where('id', (int)$lastYearAnswer)
                    ->where('screening_question_id', $lastYearQuestion->id)
                    ->first();
                $isSmokerLastYear = $opt && $opt->value === 'ya';
            }
        }

        $activityManualParentAnswer = [];
        if ($categoryCode === 'aktivitas_fisik') {
            $activityPairs = [
                'act_home_medium' => ['act_home_days', 'act_home_minutes'],
                'act_work_medium' => ['act_work_days', 'act_work_minutes'],
                'act_travel_medium' => ['act_travel_days', 'act_travel_minutes'],
                'act_sport_medium' => ['act_sport_medium_days', 'act_sport_medium_minutes'],
                'act_work_heavy' => ['act_work_heavy_days', 'act_work_heavy_minutes'],
                'act_sport_heavy' => ['act_sport_heavy_days', 'act_sport_heavy_minutes'],
            ];

            foreach ($activityPairs as $parentCode => $children) {
                $parentQuestion = $questions->firstWhere('code', $parentCode);
                $parentRaw = $answers[$parentCode] ?? null;
                $isYes = null;

                if ($parentQuestion && $parentRaw) {
                    $selectedOption = $parentQuestion->screeningOptions
                        ->firstWhere('id', (int)$parentRaw);
                    if ($selectedOption) {
                        $isYes = $selectedOption->value === 'ya';
                    }
                }

                foreach ($children as $childCode) {
                    $activityManualParentAnswer[$childCode] = $isYes;
                }
            }
        }

        foreach ($questions as $question) {
            $key = 'answers.'.$question->code;

            if ($question->type === 'single_choice') {
                $required = ['required', 'integer', Rule::exists('screening_options', 'id')->where(function ($q) use ($question) {
                    $q->where('screening_question_id', $question->id);
                })];

                if ($categoryCode === 'perilaku_merokok') {
                    $smokerCurrentOnly = ['smk_type', 'smk_years_current', 'smk_sticks_per_day'];
                    $optionalFollowup = ['smk_years_before', 'smk_quit_when'];

                    if ($isSmokerLastYear === false && in_array($question->code, $smokerCurrentOnly, true)) {
                        $rules[$key] = ['nullable'];
                        continue;
                    }

                    if (in_array($question->code, $optionalFollowup, true)) {
                        $rules[$key] = ['nullable'];
                        continue;
                    }
                }

                $rules[$key] = $required;
                continue;
            }

            // numeric
            $numericRule = ['required', 'integer', 'min:0', 'max:10080'];
            if (str_contains($question->code, '_days')) {
                $numericRule = ['required', 'integer', 'min:0', 'max:7'];
            } elseif (str_contains($question->code, '_minutes')) {
                $numericRule = ['required', 'integer', 'min:0', 'max:1440'];
            }

            if ($categoryCode === 'perilaku_merokok') {
                $numericCurrentOnly = ['smk_years_current', 'smk_sticks_per_day'];
                $numericOptional = ['smk_years_before'];

                if ($isSmokerLastYear === false && in_array($question->code, $numericCurrentOnly, true)) {
                    $rules[$key] = ['nullable'];
                    continue;
                }

                if (in_array($question->code, $numericOptional, true)) {
                    $rules[$key] = ['nullable'];
                    continue;
                }
            }

            if (
                $categoryCode === 'aktivitas_fisik' &&
                array_key_exists($question->code, $activityManualParentAnswer) &&
                $activityManualParentAnswer[$question->code] !== true
            ) {
                $rules[$key] = ['nullable'];
                continue;
            }

            $rules[$key] = $numericRule;
        }

        $request->validate($rules);
    }

    private function filterQuestionsForRespondent($questions, Respondent $respondent, string $categoryCode)
    {
        if ($categoryCode === 'demografi_dewasa' && $respondent->gender !== 'female') {
            return $questions->reject(fn (ScreeningQuestion $question) => $question->code === 'pregnancy_status')->values();
        }

        return $questions;
    }

}
