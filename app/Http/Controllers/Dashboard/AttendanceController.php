<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Cek akses
        if (Gate::denies('manage-attendance')) {
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

        return view('dashboard.attendances.index', compact('employees'));
    }

    public function show(string $nrp)
    {
        // Cek akses
        if (Gate::denies('manage-attendance')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil data pegawai + relasi attendances
        $employee = Employee::with('attendances')->where('nrp', $nrp)->firstOrFail();

        // Ambil semua absensi terbaru (paginate 20)
        $attendances = $employee->attendances()
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('dashboard.attendances.show', compact('employee', 'attendances'));
    }

    public function store(Request $request, string $nrp)
    {
        // Cek akses
        if (Gate::denies('manage-attendance')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Cari pegawai berdasarkan NRP
        $employee = Employee::where('nrp', $nrp)->firstOrFail();

        // Validasi input
        $validated = $request->validate([
            'date'      => 'required|date',
            'check_in'  => 'required|date_format:H:i',
            'check_out' => 'required|date_format:H:i',
        ]);

        // Convert jam masuk & keluar ke Carbon untuk perbandingan
        $checkIn  = Carbon::createFromFormat('H:i', $validated['check_in']);
        $checkOut = Carbon::createFromFormat('H:i', $validated['check_out']);

        // Tolak jika jam keluar <= jam masuk
        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            return back()->with('error', 'Jam keluar harus lebih besar dari jam masuk.');
        }

        // Cek apakah tanggal ini sudah ada absensi
        $exists = Attendance::where('employee_id', $employee->id)
            ->where('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Data presensi untuk tanggal ini sudah ada.');
        }

        // Simpan data presensi baru
        Attendance::create([
            'employee_id' => $employee->id,
            'date'        => $validated['date'],
            'check_in'    => $validated['check_in'],
            'check_out'   => $validated['check_out'],
        ]);

        return back()->with('success', 'Presensi berhasil ditambahkan.');
    }

    public function update(Request $request, string $nrp, int $attendanceId)
    {
        // Cek akses
        if (Gate::denies('manage-attendance')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Cari pegawai berdasarkan NRP
        $employee = Employee::where('nrp', $nrp)->firstOrFail();

        // Ambil absensi berdasarkan ID + pastikan milik karyawan ini
        $attendance = Attendance::where('id', $attendanceId)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        // Validasi input update
        $validated = $request->validate([
            'date'      => 'required|date',
            'check_in'  => 'required|date_format:H:i',
            'check_out' => 'required|date_format:H:i',
        ]);

        // Convert jam masuk & keluar ke Carbon untuk perbandingan
        $checkIn  = Carbon::createFromFormat('H:i', $validated['check_in']);
        $checkOut = Carbon::createFromFormat('H:i', $validated['check_out']);

        // Tolak jika jam keluar <= jam masuk
        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            return back()->with('error', 'Jam keluar harus lebih besar dari jam masuk.');
        }

        // Cek apakah tanggal ini bentrok dengan absensi lain
        $exists = Attendance::where('employee_id', $employee->id)
            ->where('date', $validated['date'])
            ->where('id', '!=', $attendance->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Data presensi untuk tanggal ini sudah ada.');
        }

        // Update data presensi
        $attendance->update([
            'date'      => $validated['date'],
            'check_in'  => $validated['check_in'],
            'check_out' => $validated['check_out'],
        ]);

        return back()->with('success', 'Presensi berhasil diperbarui.');
    }

    public function destroy(string $nrp, int $attendanceId)
    {
        // Cek akses
        if (Gate::denies('manage-attendance')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Cari pegawai
        $employee = Employee::where('nrp', $nrp)->firstOrFail();

        // Cari absensi milik pegawai ini
        $attendance = Attendance::where('id', $attendanceId)
            ->where('employee_id', $employee->id)
            ->firstOrFail();

        // Hapus file foto jika ada
        if ($attendance->photo_check_in) {
            Storage::disk('public')->delete($attendance->photo_check_in);
        }
        if ($attendance->photo_check_out) {
            Storage::disk('public')->delete($attendance->photo_check_out);
        }

        // Hapus record dari database
        $attendance->delete();

        return back()->with('success', 'Data presensi berhasil dihapus.');
    }

    public function myAttendanceIndex()
    {
        // Ambil user yang login
        $employee = Auth::user()->employee;

        if (!$employee) {
            return back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        // Ambil presensi milik pegawai ini
        $attendances = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('dashboard.attendances.my-index', compact('employee', 'attendances'));
    }

    public function myAttendanceCreate()
    {
        // Ambil employee login
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        // Cari presensi hari ini
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', Carbon::today())
            ->first();

        return view('dashboard.attendances.my-create', compact('employee', 'todayAttendance'));
    }

    public function myAttendanceStore(Request $request)
    {
        $employee = Auth::user()->employee;
        $today = Carbon::today();

        // Validasi foto
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek apakah sudah ada presensi hari ini
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        // Simpan foto
        $photoPath = $request->file('photo')->store('attendances', 'public');

        if (!$attendance) {
            // Belum ada → lakukan Check In
            Attendance::create([
                'employee_id'    => $employee->id,
                'date'           => $today,
                'check_in'       => now()->format('H:i:s'),
                'photo_check_in' => $photoPath,
            ]);

            return back()->with('success', 'Check In berhasil!');
        }

        if ($attendance && !$attendance->check_out) {
            // Sudah check in tapi belum check out → lakukan Check Out
            $attendance->update([
                'check_out'       => now()->format('H:i:s'),
                'photo_check_out' => $photoPath,
            ]);

            return back()->with('success', 'Check Out berhasil!');
        }

        // Sudah check in & check out
        return back()->with('error', 'Anda sudah melakukan check in & check out hari ini.');
    }
}
