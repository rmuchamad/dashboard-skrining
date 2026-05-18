@extends('layouts.app')

@section('content')
    <div class="rounded-lg border border-slate-200 bg-white p-6">
            <h4 class="text-2xl font-bold text-slate-900">Menu Ubah Password</h4>
            <div class="mb-5 mt-2 text-base text-slate-600">Gunakan password yang kuat dan mudah diingat oleh pengguna akun.</div>
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                <div class="mb-4">
                    <label class="mb-1.5 block text-base font-medium text-slate-800">Password Lama</label>
                    <input type="password" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-base" name="current_password" required>
                    @error('current_password')<div class="mt-1 text-sm text-rose-600">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="mb-1.5 block text-base font-medium text-slate-800">Password Baru</label>
                    <input type="password" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-base" name="password" required>
                </div>
                <div class="mb-5">
                    <label class="mb-1.5 block text-base font-medium text-slate-800">Konfirmasi Password Baru</label>
                    <input type="password" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-base" name="password_confirmation" required>
                </div>
                <button class="h-11 rounded-lg bg-blue-600 px-5 text-base font-semibold text-white">Ubah Password</button>
            </form>
    </div>
@endsection
