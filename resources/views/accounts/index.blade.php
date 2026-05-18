@extends('layouts.app')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h4 class="text-2xl font-bold text-slate-900">Menu Manajemen Akun</h4>
            <p class="text-sm text-slate-500">Kelola akun pengguna: lihat, buat, ubah, dan hapus.</p>
        </div>
    </div>

    <div class="mb-4 flex justify-end">
        <button id="btnOpenCreateAccount" type="button" class="h-10 rounded-md bg-[#00A99D] px-5 text-sm font-semibold text-white shadow-sm hover:bg-[#008f84]">
            Create Akun
        </button>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-base">
                <thead class="bg-slate-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Nama</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">NIK</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Role</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Jabatan</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Instansi</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Whatsapp</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3 align-top">{{ $user->name }}</td>
                        <td class="px-4 py-3 align-top">{{ $user->nik ?: '-' }}</td>
                        <td class="px-4 py-3 align-top">{{ $user->email }}</td>
                        <td class="px-4 py-3 align-top">{{ $user->role ?? 'nakes' }}</td>
                        <td class="px-4 py-3 align-top">{{ $user->jabatan ?: '-' }}</td>
                        <td class="px-4 py-3 align-top">{{ $user->instansi ?: '-' }}</td>
                        <td class="px-4 py-3 align-top">{{ $user->whatsapp ?: '-' }}</td>
                        <td class="px-4 py-3 align-top">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    type="button"
                                    class="btnViewAccount grid h-9 w-9 place-items-center rounded-md border border-slate-300 text-slate-600 hover:bg-slate-50"
                                    title="Lihat akun"
                                    aria-label="Lihat akun"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-nik="{{ $user->nik }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role ?? 'nakes' }}"
                                    data-jabatan="{{ $user->jabatan }}"
                                    data-instansi="{{ $user->instansi }}"
                                    data-whatsapp="{{ $user->whatsapp }}"
                                >👁</button>
                                <button
                                    type="button"
                                    class="btnEditAccount grid h-9 w-9 place-items-center rounded-md border border-[#00A99D] text-[#00A99D] hover:bg-teal-50"
                                    title="Update akun"
                                    aria-label="Update akun"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-nik="{{ $user->nik }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role ?? 'nakes' }}"
                                    data-jabatan="{{ $user->jabatan }}"
                                    data-instansi="{{ $user->instansi }}"
                                    data-whatsapp="{{ $user->whatsapp }}"
                                    data-update-url="{{ route('accounts.update', $user) }}"
                                >✎</button>
                            <form method="POST" action="{{ route('accounts.destroy', $user) }}">
                                @csrf
                                <button class="grid h-9 w-9 place-items-center rounded-md border border-rose-500 text-rose-600 hover:bg-rose-50" title="Hapus akun" aria-label="Hapus akun">🗑</button>
                            </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t bg-white p-4">{{ $users->links('pagination::tailwind-numeric') }}</div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        var createModal = document.getElementById('createAccountModal');
        var viewModal = document.getElementById('viewAccountModal');
        var editModal = document.getElementById('editAccountModal');
        var openCreateBtn = document.getElementById('btnOpenCreateAccount');
        var closeCreateBtn = document.getElementById('btnCloseCreateAccount');
        var closeViewBtn = document.getElementById('btnCloseViewAccount');
        var closeEditBtn = document.getElementById('btnCloseEditAccount');
        var createBackdrop = document.getElementById('createAccountBackdrop');
        var viewBackdrop = document.getElementById('viewAccountBackdrop');
        var editBackdrop = document.getElementById('editAccountBackdrop');

        function openModal(modal) {
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(modal) {
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        if (openCreateBtn) {
            openCreateBtn.addEventListener('click', function () {
                openModal(createModal);
            });
        }
        [closeCreateBtn, createBackdrop].forEach(function (el) {
            if (!el) return;
            el.addEventListener('click', function () { closeModal(createModal); });
        });
        [closeEditBtn, editBackdrop].forEach(function (el) {
            if (!el) return;
            el.addEventListener('click', function () { closeModal(editModal); });
        });
        [closeViewBtn, viewBackdrop].forEach(function (el) {
            if (!el) return;
            el.addEventListener('click', function () { closeModal(viewModal); });
        });

        document.querySelectorAll('.btnViewAccount').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('view_name').textContent = btn.getAttribute('data-name') || '-';
                document.getElementById('view_nik').textContent = btn.getAttribute('data-nik') || '-';
                document.getElementById('view_email').textContent = btn.getAttribute('data-email') || '-';
                document.getElementById('view_role').textContent = btn.getAttribute('data-role') || '-';
                document.getElementById('view_jabatan').textContent = btn.getAttribute('data-jabatan') || '-';
                document.getElementById('view_instansi').textContent = btn.getAttribute('data-instansi') || '-';
                document.getElementById('view_whatsapp').textContent = btn.getAttribute('data-whatsapp') || '-';
                openModal(viewModal);
            });
        });

        var editForm = document.getElementById('editAccountForm');
        document.querySelectorAll('.btnEditAccount').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!editForm) return;
                editForm.setAttribute('action', btn.getAttribute('data-update-url') || '');
                document.getElementById('edit_name').value = btn.getAttribute('data-name') || '';
                document.getElementById('edit_nik').value = btn.getAttribute('data-nik') || '';
                document.getElementById('edit_email').value = btn.getAttribute('data-email') || '';
                document.getElementById('edit_role').value = btn.getAttribute('data-role') || 'nakes';
                document.getElementById('edit_jabatan').value = btn.getAttribute('data-jabatan') || '';
                document.getElementById('edit_instansi').value = btn.getAttribute('data-instansi') || '';
                document.getElementById('edit_whatsapp').value = btn.getAttribute('data-whatsapp') || '';
                document.getElementById('edit_password').value = '';
                document.getElementById('edit_password_confirmation').value = '';
                openModal(editModal);
            });
        });
    })();
</script>
@endpush

<div id="createAccountModal" class="fixed inset-0 z-[240] hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div id="createAccountBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
    <div class="relative w-full max-w-4xl rounded-xl border border-slate-200 bg-white p-5 shadow-2xl">
        <div class="mb-3 flex items-center justify-between">
            <h5 class="text-lg font-bold text-slate-900">Create Akun</h5>
            <button type="button" id="btnCloseCreateAccount" class="rounded p-1 text-slate-500 hover:bg-slate-100">×</button>
        </div>
        <form method="POST" action="{{ route('accounts.store') }}" class="space-y-4">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label><input class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="name" required></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">NIK</label><input class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="nik" required></div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Email</label><input class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" type="email" name="email" required></div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Role</label>
                    <select class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="role" required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucwords(str_replace('_', ' ', $role)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Jabatan</label><input class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="jabatan" required></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Instansi</label><input class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="instansi" required></div>
            </div>
            <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Nomor Handphone/Whatsapp</label><input class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="whatsapp" required></div>
            <div class="grid gap-4 md:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Kata sandi</label><input class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" type="password" name="password" required></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi kata sandi</label><input class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" type="password" name="password_confirmation" required></div>
            </div>
            <div><button class="h-10 rounded-md bg-[#00A99D] px-5 text-sm font-semibold text-white hover:bg-[#008f84]">Simpan Akun</button></div>
        </form>
    </div>
</div>

<div id="viewAccountModal" class="fixed inset-0 z-[239] hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div id="viewAccountBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
    <div class="relative w-full max-w-xl rounded-xl border border-slate-200 bg-white p-5 shadow-2xl">
        <div class="mb-3 flex items-center justify-between">
            <h5 class="text-lg font-bold text-slate-900">Detail Akun</h5>
            <button type="button" id="btnCloseViewAccount" class="rounded p-1 text-slate-500 hover:bg-slate-100">×</button>
        </div>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div><p class="text-slate-500">Nama</p><p id="view_name" class="font-semibold text-slate-800">-</p></div>
            <div><p class="text-slate-500">NIK</p><p id="view_nik" class="font-semibold text-slate-800">-</p></div>
            <div><p class="text-slate-500">Email</p><p id="view_email" class="font-semibold text-slate-800">-</p></div>
            <div><p class="text-slate-500">Role</p><p id="view_role" class="font-semibold text-slate-800">-</p></div>
            <div><p class="text-slate-500">Jabatan</p><p id="view_jabatan" class="font-semibold text-slate-800">-</p></div>
            <div><p class="text-slate-500">Instansi</p><p id="view_instansi" class="font-semibold text-slate-800">-</p></div>
            <div class="col-span-2"><p class="text-slate-500">Whatsapp</p><p id="view_whatsapp" class="font-semibold text-slate-800">-</p></div>
        </div>
    </div>
</div>

<div id="editAccountModal" class="fixed inset-0 z-[241] hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div id="editAccountBackdrop" class="absolute inset-0 bg-slate-900/45"></div>
    <div class="relative w-full max-w-4xl rounded-xl border border-slate-200 bg-white p-5 shadow-2xl">
        <div class="mb-3 flex items-center justify-between">
            <h5 class="text-lg font-bold text-slate-900">Update Akun</h5>
            <button type="button" id="btnCloseEditAccount" class="rounded p-1 text-slate-500 hover:bg-slate-100">×</button>
        </div>
        <form id="editAccountForm" method="POST" action="#" class="space-y-4">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label><input id="edit_name" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="name" required></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">NIK</label><input id="edit_nik" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="nik" required></div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Email</label><input id="edit_email" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" type="email" name="email" required></div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Role</label>
                    <select id="edit_role" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="role" required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucwords(str_replace('_', ' ', $role)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Jabatan</label><input id="edit_jabatan" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="jabatan" required></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Instansi</label><input id="edit_instansi" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="instansi" required></div>
            </div>
            <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Nomor Handphone/Whatsapp</label><input id="edit_whatsapp" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" name="whatsapp" required></div>
            <div class="grid gap-4 md:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Kata sandi baru (opsional)</label><input id="edit_password" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" type="password" name="password"></div>
                <div><label class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi kata sandi</label><input id="edit_password_confirmation" class="h-11 w-full rounded-md border border-slate-300 px-3 text-sm" type="password" name="password_confirmation"></div>
            </div>
            <div><button class="h-10 rounded-md bg-[#00A99D] px-5 text-sm font-semibold text-white hover:bg-[#008f84]">Update Akun</button></div>
        </form>
    </div>
</div>
