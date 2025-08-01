<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Cek akses
        if (Gate::denies('manage-employee')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Validasi Search Form
        $validated = $request->validate([
            'status' => 'nullable|string|in:Aktif,Pensiun,Meninggal Dunia,Diberhentikan',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'search' => 'nullable|string|min:1',
        ]);

        // Ambil Nilai
        $status = $validated['status'] ?? null;
        $gender = $validated['gender'] ?? null;
        $start_date = $validated['start_date'] ?? null;
        $end_date = $validated['end_date'] ?? null;
        $search = $validated['search'] ?? null;

        // Semua Karyawan Dengan Data Berita dan Laporan
        $employees = Employee::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('nrp', 'LIKE', "{$search}%");
            });
        })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($gender, function ($query) use ($gender) {
                return $query->where('gender', $gender);
            })
            ->when($start_date, function ($query) use ($start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->orderBy('name', 'ASC')
            ->paginate(20);

        return view('dashboard.employees.index', compact('employees'));
    }

    public function create()
    {
        // Cek akses
        if (Gate::denies('manage-employee')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('dashboard.employees.create');
    }

    public function store(Request $request)
    {
        // Cek akses
        if (Gate::denies('manage-employee')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Validasi Input
        $validated = $request->validate(
            [
                // Data Pegawai
                'nrp' => 'required|numeric|unique:employees,nrp',
                'name' => 'required|string|max:255',
                'gender' => 'required|in:Laki-laki,Perempuan',
                'position' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'required|date',
                'address' => 'required|string',
                'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

                // Data User
                'username' => 'required|string|max:255|unique:users,name',
                'email' => 'required|email:rfc,dns|max:255|unique:users,email',
                'role' => 'required|in:Admin,Pengguna',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:255',
                    'confirmed',
                    'regex:/[0-9]/',
                ],
            ],
            [
                'password.regex' => 'Password harus mengandung minimal 1 angka.',
            ]
        );

        // Simpan avatar (jika ada)
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('employees', 'public');
        } else {
            $avatarPath = null;
        }

        // Simpan user
        $user = User::create([
            'name' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        // Simpan employee & hubungkan ke user
        $employee = Employee::create([
            'user_id' => $user->id,
            'nrp' => $validated['nrp'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'position' => $validated['position'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
            'avatar' => $avatarPath,
        ]);

        return redirect()->back()->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit(string $nrp)
    {
        // Cek akses
        if (Gate::denies('manage-employee')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil data pegawai berdasarkan NRP
        $employee = Employee::where('nrp', $nrp)->firstOrFail();

        return view('dashboard.employees.edit', compact('employee'));
    }

    public function update(Request $request, string $nrp)
    {
        // Cek akses
        if (Gate::denies('manage-employee')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil employee berdasarkan NRP
        $employee = Employee::where('nrp', $nrp)->firstOrFail();

        // Validasi Input
        $validated = $request->validate(
            [
                // Data Pegawai
                'nrp' => 'required|numeric|unique:employees,nrp,' . $employee->id,
                'name' => 'required|string|max:255',
                'gender' => 'required|in:Laki-laki,Perempuan',
                'position' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'required|date',
                'address' => 'required|string',
                'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'status' => 'required|in:Aktif,Pensiun,Meninggal Dunia,Diberhentikan',

                // Data User
                'username' => 'required|string|max:255|unique:users,name,' . $employee->user->id,
                'email' => 'required|email:rfc,dns|max:255|unique:users,email,' . $employee->user->id,
                'role' => 'required|in:Admin,Pengguna',
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'max:255',
                    'confirmed',
                    'regex:/[0-9]/',
                ],
            ],
            [
                'password.regex' => 'Password harus mengandung minimal 1 angka.',
            ]
        );

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
        $employee->user->update([
            'name' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $employee->user->password,
        ]);

        // Update data employee
        $employee->update([
            'nrp' => $validated['nrp'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'position' => $validated['position'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
            'status' => $validated['status'],
            'avatar' => $avatarPath,
        ]);

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(string $nrp)
    {
        // Cek akses
        if (Gate::denies('manage-employee')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil data pegawai berdasarkan NRP
        $employee = Employee::where('nrp', $nrp)->firstOrFail();

        // Hapus avatar jika ada
        if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
            Storage::disk('public')->delete($employee->avatar);
        }

        // Hapus user login terkait (jika ada relasi ke user)
        if ($employee->user) {
            $employee->user->delete();
        }

        // Hapus data pegawai
        $employee->delete();

        return redirect()->back()->with('success', 'Data pegawai berhasil dihapus.');
    }
}
