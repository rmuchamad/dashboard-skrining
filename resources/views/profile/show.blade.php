@extends('layouts.app')

@section('content')
    @php($user = auth()->user())
    @php($nikValue = old('nik', $user?->nik ?: ($user?->id ? str_pad((string)$user->id, 16, '0', STR_PAD_LEFT) : '')))
    <div class="mb-4">
        <a href="{{ route('individu.index') }}" class="inline-flex items-center gap-2 text-2xl font-bold text-slate-800 hover:text-slate-900">
            <span>←</span>
            <span>Profil</span>
        </a>
        <p class="mt-1 text-sm text-slate-500">Lengkapi profil dengan mengisi data berikut</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="rounded-xl border border-slate-200 p-4">
            <div class="mb-3 flex flex-wrap items-center gap-2">
                <h5 class="text-xl font-semibold text-slate-800">Informasi akun</h5>
                <span class="rounded-full bg-rose-500 px-2 py-0.5 text-xs font-bold text-white">WAJIB ISI</span>
            </div>

            <div class="mb-4 flex flex-wrap items-start justify-between gap-4 rounded-lg bg-slate-50 p-3">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 grid h-7 w-7 place-items-center rounded-full bg-slate-200 text-slate-500">i</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">{{ $user?->name ?? '-' }}</p>
                        <p class="text-sm text-slate-600">NIK {{ $nikValue !== '' ? $nikValue : '-' }}</p>
                        <p class="text-sm text-teal-700 underline underline-offset-2">{{ $user?->email ?? '-' }}</p>
                    </div>
                </div>
                <p class="pt-1 text-sm text-slate-600">Kata sandi •••••••</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user?->name) }}"
                                class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm text-slate-700"
                                required
                            >
                            @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">NIK</label>
                            <input
                                type="text"
                                name="nik"
                                value="{{ $nikValue }}"
                                class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm text-slate-700"
                                required
                            >
                            @error('nik')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user?->email) }}"
                                class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm text-slate-700"
                                required
                            >
                            @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jabatan</label>
                        <input
                            type="text"
                            name="jabatan"
                            value="{{ old('jabatan', $user?->jabatan) }}"
                            class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm text-slate-700"
                            required
                        >
                        @error('jabatan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Instansi</label>
                        <input
                            type="text"
                            name="instansi"
                            value="{{ old('instansi', $user?->instansi) }}"
                            class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm text-slate-700"
                            required
                        >
                        @error('instansi')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nomor Handphone/Whatsapp</label>
                        <div class="flex items-center">
                            <span class="inline-flex h-11 items-center rounded-l-md border border-r-0 border-slate-300 bg-slate-50 px-3 text-sm text-slate-600">+62</span>
                            <input
                                type="text"
                                name="whatsapp"
                                value="{{ old('whatsapp', $user?->whatsapp) }}"
                                class="h-11 w-full rounded-r-md border border-slate-300 px-3 text-sm text-slate-700"
                                required
                            >
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Masukkan nomor whatsapp tanpa menggunakan angka 0</p>
                        @error('whatsapp')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="h-10 rounded-md bg-[#00A99D] px-5 text-sm font-semibold text-white hover:bg-[#008f84]">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
