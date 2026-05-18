<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Dashboard ASIK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-50 to-slate-50 text-base antialiased">
<div class="mx-auto max-w-md px-4 pt-24">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-xl">
        <span class="inline-block rounded-full bg-blue-100 px-3 py-1.5 text-sm font-semibold text-blue-800">Web Dashboard ASIK</span>
        <h4 class="mt-4 text-2xl font-bold text-slate-900">Login Dashboard ASIK</h4>
        <p class="mb-6 mt-2 text-base text-slate-600">Masuk untuk akses menu layanan dan pemeriksaan.</p>
                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="mb-1.5 block text-base font-medium text-slate-800">Email</label>
                            <input type="email" name="email" value="{{ old('email', 'admin@admin.com') }}" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-base" required>
                            @error('email')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-5">
                            <label class="mb-1.5 block text-base font-medium text-slate-800">Password</label>
                            <input type="password" name="password" value="admin" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-base" required>
                        </div>
                        <button class="h-11 w-full rounded-lg bg-blue-600 text-base font-semibold text-white" type="submit">Masuk</button>
                    </form>
    </div>
</div>
</body>
</html>
