@extends('layouts.app')

@section('content')
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <h4 class="text-2xl font-bold text-slate-900">Rapor Pemeriksaan</h4>
            <a class="rounded-lg border-2 border-blue-500 px-4 py-2 text-base font-semibold text-blue-600 hover:bg-blue-50" href="{{ route('pelayanan.report_pdf', $session) }}">Unduh PDF</a>
        </div>
        <div class="mb-5 grid gap-3 sm:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-base"><strong class="text-slate-800">Nama:</strong> {{ $session->respondent?->name }}</div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-base"><strong class="text-slate-800">NIK:</strong> {{ $session->respondent?->nik }}</div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-base"><strong class="text-slate-800">Tiket:</strong> {{ $session->ticket_number }}</div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="w-full text-base">
                <thead class="bg-slate-100 text-left text-sm font-semibold uppercase text-slate-700">
                <tr><th class="px-4 py-3">Pertanyaan</th><th class="px-4 py-3">Jawaban</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @foreach($session->screeningAnswers as $answer)
                    <tr>
                        <td class="px-4 py-3 text-slate-900">{{ $answer->screeningQuestion?->text }}</td>
                        <td class="px-4 py-3 text-slate-800">{{ $answer->screeningOption?->label ?? $answer->text_answer ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
