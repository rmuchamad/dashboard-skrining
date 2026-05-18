<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('profile.show');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:32|unique:users,nik,'.auth()->id(),
            'email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'jabatan' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:32',
        ]);

        $request->user()->update($data);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function passwordForm(): View
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($data['current_password'], (string)$request->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $request->user()->update(['password' => $data['password']]);
        return back()->with('success', 'Password berhasil diubah.');
    }
}
