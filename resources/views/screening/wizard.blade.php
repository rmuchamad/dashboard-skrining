@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="text-3xl font-bold text-slate-900">{{ $title }}</h2>
        <p class="mt-1 text-base text-slate-600">Isi jawaban sesuai kategori pemeriksaan mandiri.</p>
    </div>

    <form method="POST" action="{{ route('screening.wizard.store', ['kategori' => $kategori]) }}" class="rounded-lg border border-slate-200 bg-white shadow-sm">
        @csrf

        <div class="p-6 md:p-8">
            @foreach($questions as $question)
                @include('screening.question', ['question' => $question, 'prefill' => $prefill])
            @endforeach
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4 md:px-7">
            <button type="submit" class="rounded-lg bg-[#00A99D] px-6 py-2.5 text-base font-semibold text-white hover:bg-[#008f84]">Kirim</button>
            <a class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-base font-semibold text-slate-800 hover:bg-slate-50" href="{{ $backToMainUrl }}">Kembali ke Halaman Utama</a>
        </div>
    </form>
@endsection

@push('scripts')
    @if($categoryCode === 'perilaku_merokok')
        <script>
            (function () {
                var questionEls = Array.from(document.querySelectorAll('[data-question-code]'));
                if (!questionEls.length) return;

                function getQuestion(code) {
                    return questionEls.find(function (el) {
                        return el.getAttribute('data-question-code') === code;
                    }) || null;
                }

                function setRequired(el, required) {
                    if (!el) return;
                    el.querySelectorAll('input[data-default-required="1"]').forEach(function (input) {
                        if (required) {
                            input.setAttribute('required', 'required');
                        } else {
                            input.removeAttribute('required');
                        }
                    });
                    var mark = el.querySelector('.q-required-mark');
                    var badge = el.querySelector('.q-required-badge');
                    if (mark) mark.classList.toggle('hidden', !required);
                    if (badge) {
                        badge.textContent = required ? 'Wajib' : 'Opsional';
                        badge.classList.toggle('bg-slate-50', required);
                        badge.classList.toggle('text-slate-600', required);
                        badge.classList.toggle('bg-amber-50', !required);
                        badge.classList.toggle('text-amber-700', !required);
                    }
                }

                function setVisible(el, visible) {
                    if (!el) return;
                    el.classList.toggle('hidden', !visible);
                    if (!visible) {
                        el.querySelectorAll('input[type="radio"]').forEach(function (input) {
                            input.checked = false;
                        });
                        el.querySelectorAll('input[type="number"]').forEach(function (input) {
                            input.value = '';
                        });
                    }
                }

                var qLastYear = getQuestion('smk_last_year');
                var qPassive = getQuestion('smk_passive_last_month');
                var qSmokerType = getQuestion('smk_type');
                var qSmokerYears = getQuestion('smk_years_current');
                var qSmokerSticks = getQuestion('smk_sticks_per_day');
                var qBeforeYears = getQuestion('smk_years_before');
                var qQuitWhen = getQuestion('smk_quit_when');

                function selectedLastYearValue() {
                    var checked = document.querySelector('input[name="answers[smk_last_year]"]:checked');
                    if (!checked) return '';
                    var label = checked.closest('label');
                    var txt = label ? (label.textContent || '').trim().toLowerCase() : '';
                    return txt === 'ya' ? 'ya' : (txt === 'tidak' ? 'tidak' : '');
                }

                function syncSmokingFlow() {
                    var v = selectedLastYearValue();

                    // default awal: hanya 2 pertanyaan utama tampil
                    if (!v) {
                        setVisible(qSmokerType, false);
                        setVisible(qSmokerYears, false);
                        setVisible(qSmokerSticks, false);
                        setVisible(qBeforeYears, false);
                        setVisible(qQuitWhen, false);
                        setRequired(qSmokerType, false);
                        setRequired(qSmokerYears, false);
                        setRequired(qSmokerSticks, false);
                        setRequired(qBeforeYears, false);
                        setRequired(qQuitWhen, false);
                        setVisible(qLastYear, true);
                        setVisible(qPassive, true);
                        return;
                    }

                    if (v === 'ya') {
                        setVisible(qSmokerType, true);
                        setVisible(qSmokerYears, true);
                        setVisible(qSmokerSticks, true);
                        setRequired(qSmokerType, true);
                        setRequired(qSmokerYears, true);
                        setRequired(qSmokerSticks, true);

                        setVisible(qBeforeYears, false);
                        setVisible(qQuitWhen, false);
                        setRequired(qBeforeYears, false);
                        setRequired(qQuitWhen, false);
                        return;
                    }

                    setVisible(qSmokerType, false);
                    setVisible(qSmokerYears, false);
                    setVisible(qSmokerSticks, false);
                    setRequired(qSmokerType, false);
                    setRequired(qSmokerYears, false);
                    setRequired(qSmokerSticks, false);

                    setVisible(qBeforeYears, true);
                    setVisible(qQuitWhen, true);
                    setRequired(qBeforeYears, false);
                    setRequired(qQuitWhen, false);
                }

                document.querySelectorAll('input[name="answers[smk_last_year]"]').forEach(function (el) {
                    el.addEventListener('change', syncSmokingFlow);
                });
                syncSmokingFlow();
            })();
        </script>
    @endif
    @if($categoryCode === 'aktivitas_fisik')
        <script>
            (function () {
                var questionEls = Array.from(document.querySelectorAll('[data-question-code]'));
                if (!questionEls.length) return;

                function getQuestion(code) {
                    return questionEls.find(function (el) {
                        return el.getAttribute('data-question-code') === code;
                    }) || null;
                }

                function setRequired(el, required) {
                    if (!el) return;
                    el.querySelectorAll('input[data-default-required="1"]').forEach(function (input) {
                        if (required) {
                            input.setAttribute('required', 'required');
                        } else {
                            input.removeAttribute('required');
                        }
                    });
                    var mark = el.querySelector('.q-required-mark');
                    var badge = el.querySelector('.q-required-badge');
                    if (mark) mark.classList.toggle('hidden', !required);
                    if (badge) {
                        badge.textContent = required ? 'Wajib' : 'Opsional';
                        badge.classList.toggle('bg-slate-50', required);
                        badge.classList.toggle('text-slate-600', required);
                        badge.classList.toggle('bg-amber-50', !required);
                        badge.classList.toggle('text-amber-700', !required);
                    }
                }

                function setVisible(el, visible) {
                    if (!el) return;
                    el.classList.toggle('hidden', !visible);
                    if (!visible) {
                        el.querySelectorAll('input[type="radio"]').forEach(function (input) {
                            input.checked = false;
                        });
                        el.querySelectorAll('input[type="number"]').forEach(function (input) {
                            input.value = '';
                        });
                    }
                }

                function selectedYesNoValue(parentCode) {
                    var checked = document.querySelector('input[name="answers[' + parentCode + ']"]:checked');
                    if (!checked) return '';
                    var label = checked.closest('label');
                    var txt = label ? (label.textContent || '').trim().toLowerCase() : '';
                    return txt === 'ya' ? 'ya' : (txt === 'tidak' ? 'tidak' : '');
                }

                var mapping = [
                    { parent: 'act_home_medium', children: ['act_home_days', 'act_home_minutes'] },
                    { parent: 'act_work_medium', children: ['act_work_days', 'act_work_minutes'] },
                    { parent: 'act_travel_medium', children: ['act_travel_days', 'act_travel_minutes'] },
                    { parent: 'act_sport_medium', children: ['act_sport_medium_days', 'act_sport_medium_minutes'] },
                    { parent: 'act_work_heavy', children: ['act_work_heavy_days', 'act_work_heavy_minutes'] },
                    { parent: 'act_sport_heavy', children: ['act_sport_heavy_days', 'act_sport_heavy_minutes'] }
                ];

                function syncActivityManualQuestions() {
                    mapping.forEach(function (group) {
                        var parentValue = selectedYesNoValue(group.parent);
                        group.children.forEach(function (childCode) {
                            var childEl = getQuestion(childCode);
                            var shouldShow = parentValue === 'ya';
                            setVisible(childEl, shouldShow);
                            setRequired(childEl, shouldShow);
                        });
                    });
                }

                mapping.forEach(function (group) {
                    document.querySelectorAll('input[name="answers[' + group.parent + ']"]').forEach(function (el) {
                        el.addEventListener('change', syncActivityManualQuestions);
                    });
                });

                syncActivityManualQuestions();
            })();
        </script>
    @endif
@endpush
