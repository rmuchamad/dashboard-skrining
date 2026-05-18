<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Skrining CKG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        if (typeof Chart !== 'undefined') {
            Chart.defaults.font.size = 14;
            Chart.defaults.font.family = 'system-ui, "Segoe UI", sans-serif';
        }
    </script>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 text-base antialiased">
<div class="flex min-h-screen">
    <aside class="w-64 shrink-0 border-r border-slate-200 bg-white">
        <div class="border-b border-slate-200 px-5 py-4">
            <a class="text-lg font-bold text-slate-900" href="{{ route('individu.index') }}">Dashboard ASIK</a>
        </div>
        @php($normalizedRole = auth()->check() ? str_replace([' ', '-'], '_', strtolower((string) auth()->user()->role)) : '')
        @php($canManageAndViewDashboard = auth()->check() && (in_array($normalizedRole, ['admin', 'super_admin'], true) || strtolower((string) auth()->user()->email) === 'admin@admin.com'))
        <nav class="space-y-1 p-3 text-sm">
            <a class="block rounded-md px-3 py-2 {{ request()->routeIs('individu.*') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}" href="{{ route('individu.index') }}">Cari/Daftarkan Individu</a>
            <a class="block rounded-md px-3 py-2 {{ request()->routeIs('pelayanan.*') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}" href="{{ route('pelayanan.index') }}">Pelayanan</a>
            <a class="block rounded-md px-3 py-2 {{ request()->routeIs('profile.show') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}" href="{{ route('profile.show') }}">Profil</a>
            <a class="block rounded-md px-3 py-2 {{ request()->routeIs('profile.password.*') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}" href="{{ route('profile.password.form') }}">Ubah Password</a>
            @if($canManageAndViewDashboard)
                <a class="block rounded-md px-3 py-2 {{ request()->routeIs('accounts.*') ? 'bg-blue-50 font-semibold text-blue-700' : 'text-slate-700 hover:bg-slate-100' }}" href="{{ route('accounts.index') }}">Manajemen Akun</a>
            @endif
            
        </nav>
        <div class="p-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Logout</button>
            </form>
        </div>
    </aside>

    <main class="min-w-0 flex-1 px-4 py-6 md:px-6">
        @if(session('success'))
            <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-4 py-3 text-base text-emerald-800">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 rounded border border-rose-300 bg-rose-50 px-4 py-3 text-base text-rose-700">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </main>
</div>

@include('partials.pendaftaran_modal')

@if(session('ticket_popup'))
    <div id="ticketPopupModal" class="fixed inset-0 z-[230] flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-slate-900/45" id="ticketPopupBackdrop"></div>
        <div class="relative w-full max-w-sm rounded-xl border border-slate-200 bg-white p-5 shadow-2xl">
            <h3 class="text-lg font-bold text-slate-900">Nomor Tiket Peserta</h3>
            <p class="mt-2 text-sm text-slate-600">Pendaftaran berhasil. Nomor tiket peserta:</p>
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-center text-2xl font-extrabold tracking-wider text-emerald-700">
                {{ session('ticket_popup') }}
            </div>
            <div class="mt-5 flex justify-end">
                <button type="button" id="ticketPopupOk" class="rounded-lg bg-[#00A99D] px-5 py-2 text-sm font-semibold text-white hover:bg-[#008f84]">OK</button>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var modal = document.getElementById('ticketPopupModal');
            var okBtn = document.getElementById('ticketPopupOk');
            var backdrop = document.getElementById('ticketPopupBackdrop');
            function closeTicketPopup() {
                if (!modal) return;
                modal.remove();
            }
            if (okBtn) okBtn.addEventListener('click', closeTicketPopup);
            if (backdrop) backdrop.addEventListener('click', closeTicketPopup);
        })();
    </script>
@endif

@stack('scripts')
</body>
</html>

