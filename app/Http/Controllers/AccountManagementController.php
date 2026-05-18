<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountManagementController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdminOrSuperAdmin();
        $users = User::query()->latest()->paginate(10);
        $roles = $this->assignableRoles();
        return view('accounts.index', compact('users', 'roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdminOrSuperAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:32|unique:users,nik',
            'email' => 'required|email|max:255|unique:users,email',
            'jabatan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:32',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', 'in:'.implode(',', $this->assignableRoles())],
        ]);

        User::query()->create([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'email' => $data['email'],
            'role' => $data['role'],
            'jabatan' => $data['jabatan'],
            'instansi' => $data['instansi'],
            'whatsapp' => $data['whatsapp'],
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdminOrSuperAdmin();
        $roles = $this->assignableRoles();

        if (($request->user()->role === 'admin') && ($user->role === 'super_admin')) {
            return back()->withErrors(['account' => 'Admin tidak dapat mengubah akun Super Admin.']);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:32|unique:users,nik,'.$user->id,
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'jabatan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:32',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => ['required', 'in:'.implode(',', $roles)],
        ]);

        $payload = [
            'name' => $data['name'],
            'nik' => $data['nik'],
            'email' => $data['email'],
            'role' => $data['role'],
            'jabatan' => $data['jabatan'],
            'instansi' => $data['instansi'],
            'whatsapp' => $data['whatsapp'],
        ];
        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }
        $user->update($payload);

        return back()->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeAdminOrSuperAdmin();
        if ($user->id === auth()->id()) {
            return back()->withErrors(['account' => 'Tidak bisa menghapus akun sendiri.']);
        }
        if ((auth()->user()->role === 'admin') && ($user->role === 'super_admin')) {
            return back()->withErrors(['account' => 'Admin tidak dapat menghapus akun Super Admin.']);
        }

        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }

    private function authorizeAdminOrSuperAdmin(): void
    {
        $isDefaultSuperAdminEmail = auth()->check() && strtolower((string) auth()->user()->email) === 'admin@admin.com';
        $role = $this->normalizedRole();
        abort_unless(auth()->check() && (in_array($role, ['admin', 'super_admin'], true) || $isDefaultSuperAdminEmail), 403);
    }

    /**
     * @return array<int, string>
     */
    private function assignableRoles(): array
    {
        $isDefaultSuperAdminEmail = auth()->check() && strtolower((string) auth()->user()->email) === 'admin@admin.com';
        if (auth()->check() && ($this->normalizedRole() === 'super_admin' || $isDefaultSuperAdminEmail)) {
            return ['super_admin', 'admin', 'nakes'];
        }

        return ['admin', 'nakes'];
    }

    private function normalizedRole(): string
    {
        $role = auth()->check() ? (string) auth()->user()->role : '';
        return str_replace([' ', '-'], '_', strtolower($role));
    }
}
