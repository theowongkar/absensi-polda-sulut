<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Dashboard</x-slot>

    {{-- Statistik Pegawai --}}
    <section class="space-y-4">
        <x-shared.section-title>Statistik Pegawai</x-shared.section-title>
        <div class="grid grid-cols-3 lg:grid-cols-5 gap-4">
            <x-cards.status-card :count="$totalEmployees" title="Total Pegawai" subtitle="Total pegawai" color="purple" />
            <x-cards.status-card :count="$activeEmployees" title="Aktif" subtitle="Pegawai aktif" color="green" />
            <x-cards.status-card :count="$retiredEmployees" title="Pensiun" subtitle="Pegawai pensiun" color="blue" />
            <x-cards.status-card :count="$deceasedEmployees" title="Meninggal Dunia" subtitle="Pegawai meninggal dunia"
                color="yellow" />
            <x-cards.status-card :count="$dismissedEmployees" title="Diberhentikan" subtitle="Pegawai diberhentikan"
                color="red" />
        </div>
    </section>

    {{-- Statistik Gender --}}
    <section class="space-y-4 mt-8">
        <x-shared.section-title>Statistik Gender</x-shared.section-title>
        <div class="grid grid-cols-2 gap-4">
            <x-cards.status-card :count="$maleEmployees" title="Laki-laki" subtitle="Pegawai laki-laki" color="blue" />
            <x-cards.status-card :count="$femaleEmployees" title="Perempuan" subtitle="Pegawai perempuan" color="pink" />
        </div>
    </section>

    {{-- Top 5 Karyawan Paling Aktif --}}
    <section class="space-y-4 mt-8">
        <x-shared.section-title>Top 5 Karyawan Paling Aktif Tahun {{ now()->year }}</x-shared.section-title>

        @if ($topActiveEmployees->count())
            <div class="bg-white rounded-xl p-4 shadow divide-y">
                @foreach ($topActiveEmployees as $index => $employee)
                    <div class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-bold">{{ $index + 1 }}. {{ $employee->name }}</p>
                            <p class="text-sm text-gray-500">{{ $employee->position }}</p>
                        </div>
                        <div class="text-sm">
                            Hadir: <span class="font-semibold">{{ $employee->hadir_count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Belum ada data absensi tahun ini.</p>
        @endif
    </section>
</x-app-layout>
