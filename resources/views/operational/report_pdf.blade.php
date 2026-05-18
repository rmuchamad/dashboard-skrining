<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor {{ $session->ticket_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.45; color: #111; }
        h3 { margin: 0 0 10px 0; font-size: 16px; }
        .meta { margin-bottom: 14px; }
        .meta td { border: none; padding: 2px 12px 2px 0; vertical-align: top; }
        .meta .k { font-weight: bold; width: 140px; }
        table.q { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.q th, table.q td { border: 1px solid #bbb; padding: 7px; vertical-align: top; }
        table.q th { background: #eee; text-align: left; font-size: 12px; }
    </style>
</head>
<body>
    <h3>Rapor Pemeriksaan ASIK (CKG)</h3>
    <table class="meta">
        <tr><td class="k">Nama peserta</td><td>{{ $session->respondent?->name }}</td></tr>
        <tr><td class="k">NIK</td><td>{{ $session->respondent?->nik }}</td></tr>
        <tr><td class="k">Nomor tiket</td><td>{{ $session->ticket_number }}</td></tr>
        <tr><td class="k">Tanggal pemeriksaan</td><td>{{ optional($session->screened_at)->format('d/m/Y') }}</td></tr>
        <tr><td class="k">Kategori risiko</td><td>{{ $session->risk_category }} (skor {{ $session->risk_score }})</td></tr>
        <tr><td class="k">WA peserta</td><td>{{ $session->respondent?->phone ?? '—' }}</td></tr>
        <tr><td class="k">Nama wali</td><td>{{ $session->respondent?->guardian_name ?? '—' }}</td></tr>
        <tr><td class="k">WA wali</td><td>{{ $session->respondent?->guardian_phone ?? '—' }}</td></tr>
    </table>
    <table class="q">
        <thead><tr><th>Pertanyaan</th><th>Jawaban</th></tr></thead>
        <tbody>
        @foreach($session->screeningAnswers as $answer)
            <tr>
                <td>{{ $answer->screeningQuestion?->text }}</td>
                <td>{{ $answer->screeningOption?->label ?? $answer->text_answer ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
