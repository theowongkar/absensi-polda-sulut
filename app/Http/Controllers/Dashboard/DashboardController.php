<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index()
    {
        $currentYear = Carbon::now()->year;

        // Statistik Status Pegawai
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'Aktif')->count();
        $retiredEmployees = Employee::where('status', 'Pensiun')->count();
        $deceasedEmployees = Employee::where('status', 'Meninggal Dunia')->count();
        $dismissedEmployees = Employee::where('status', 'Diberhentikan')->count();

        // Statistik Gender
        $maleEmployees = Employee::where('gender', 'Laki-laki')->count();
        $femaleEmployees = Employee::where('gender', 'Perempuan')->count();

        $topActiveEmployees = Employee::select('employees.*', DB::raw('COUNT(attendances.id) as hadir_count'))
            ->join('attendances', 'employees.id', '=', 'attendances.employee_id')
            ->whereYear('attendances.date', $currentYear)
            ->groupBy('employees.id')
            ->orderByDesc('hadir_count')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalEmployees',
            'activeEmployees',
            'retiredEmployees',
            'deceasedEmployees',
            'dismissedEmployees',
            'maleEmployees',
            'femaleEmployees',
            'topActiveEmployees'
        ));
    }
}
