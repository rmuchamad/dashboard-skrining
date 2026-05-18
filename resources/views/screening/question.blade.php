@php

    /** @var \App\Models\ScreeningQuestion $question */

    $field = 'answers['.$question->code.']';

    $oldVal = old($field, $prefill[$question->code] ?? null);

@endphp



<div class="mb-5 rounded-lg border border-slate-200 bg-white p-5" data-question-code="{{ $question->code }}">

    <div class="mb-3 flex justify-between gap-3">

        <div class="text-lg font-semibold text-slate-900">{{ $question->text }} <span class="q-required-mark text-rose-500">*</span></div>

        <span class="q-required-badge shrink-0 rounded border border-slate-200 bg-slate-50 px-2.5 py-1 text-sm text-slate-600">Wajib</span>

    </div>



    @if($question->type === 'single_choice')

        <div class="grid gap-2.5">

            @foreach($question->screeningOptions as $opt)

                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 hover:bg-slate-50">

                    <input

                        class="h-5 w-5 text-blue-600"

                        type="radio"

                        name="{{ $field }}"

                        value="{{ $opt->id }}"

                        @checked((string)$oldVal === (string)$opt->id)

                        @if($loop->first) required data-default-required="1" @endif

                    >

                    <span class="text-base text-slate-800">{{ $opt->label }}</span>

                </label>

            @endforeach

        </div>

        @error($field)

            <div class="mt-2 text-sm text-rose-600">{{ $message }}</div>

        @enderror

    @else

        <input

            type="number"

            name="{{ $field }}"

            value="{{ $oldVal }}"

            class="w-full rounded-lg border px-3 py-2.5 text-base @error($field) border-rose-500 @else border-slate-300 @enderror"

            min="0"

            step="1"

            required
            data-default-required="1"

        >

        <p class="mt-2 text-sm text-slate-600">Isi angka sesuai kuesioner (hari/menit).</p>

        @error($field)

            <div class="mt-1 text-sm text-rose-600">{{ $message }}</div>

        @enderror

    @endif

</div>

