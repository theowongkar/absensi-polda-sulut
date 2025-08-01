<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        // Ambil data user yang login
        $user = Auth::user();

        return view('auth.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /**
         * @var \App\Models\User $user
         */

        // Ambil employee berdasarkan NIP
        $user = Auth::user();
        $employee = $user->employee;

        // Validasi Input
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,name,' . $user->id,
            'email' => 'required|email:rfc,dns|max:255|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/[0-9]/'
            ],
            'role' => $user->role,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'password.regex' => 'Password harus mengandung minimal 1 angka.',
        ]);

        // Update avatar jika ada file baru
        if ($request->hasFile('avatar')) {
            if ($employee->avatar) {
                Storage::disk('public')->delete($employee->avatar);
            }
            $avatarPath = $request->file('avatar')->store('employees', 'public');
        } else {
            $avatarPath = $employee->avatar;
        }

        // Update user lewat relasi
        $user->update([
            'name' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $employee->user->password,
        ]);

        // Update avatar di employee
        $employee->update([
            'avatar' => $avatarPath,
        ]);


        // Jika password diubah, logout user
        $passwordChanged = !empty($validated['password']);
        if ($passwordChanged) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login');
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
