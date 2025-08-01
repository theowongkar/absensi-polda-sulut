<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Presensi Saya</x-slot>

    <section class="space-y-2">
        {{-- Header Card --}}
        <div class="bg-gray-50 rounded-lg border border-gray-300 shadow">
            <div class="p-2 space-y-2">
                <div class="flex flex-col lg:flex-row items-center lg:gap-5">
                    <p>Nama: {{ $employee->name }}</p>
                    <p>NRP: {{ $employee->nrp }}</p>
                </div>

                {{-- Pagination --}}
                <div class="overflow-x-auto">
                    {{ $attendances->withQueryString()->links('pagination::custom') }}
                </div>
            </div>
        </div>

        {{-- Flash Message --}}
        <x-alerts.flash-message />

        {{-- Tabel Presensi --}}
        <div class="bg-white rounded-lg border border-gray-300 shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-[#ffdd19]">
                    <tr>
                        <th class="p-2 font-normal text-center border-r border-yellow-400">#</th>
                        <th class="p-2 font-normal text-center border-r border-yellow-400">Tanggal</th>
                        <th class="p-2 font-normal text-center border-r border-yellow-400">Jam Masuk</th>
                        <th class="p-2 font-normal text-center border-r border-yellow-400">Jam Keluar</th>
                        <th class="p-2 font-normal text-center border-r border-yellow-400">Foto Masuk</th>
                        <th class="p-2 font-normal text-center border-r border-yellow-400">Foto Keluar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-blue-50">
                            {{-- Nomor --}}
                            <td class="p-2 text-center border-r border-gray-300">
                                {{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration }}
                            </td>
                            {{-- Tanggal --}}
                            <td class="p-2 text-center border-r border-gray-300 whitespace-nowrap">
                                {{ $attendance->date->translatedFormat('d F Y') }}
                            </td>
                            {{-- Jam Masuk --}}
                            <td class="p-2 text-center border-r border-gray-300 whitespace-nowrap">
                                {{ substr($attendance->check_in, 0, 5) }}
                            </td>
                            {{-- Jam Keluar --}}
                            <td class="p-2 text-center border-r border-gray-300 whitespace-nowrap">
                                {{ substr($attendance->check_out, 0, 5) }}
                            </td>
                            {{-- Foto Masuk --}}
                            <td class="p-2 border-r border-gray-300 whitespace-nowrap">
                                <div class="flex justify-center">
                                    <img src="{{ $attendance->photo_check_in ? asset('storage/' . $attendance->photo_check_in) : asset('img/application-logo.svg') }}"
                                        alt="Foto Check In" class="w-10 h-10 object-contain">
                                </div>
                            </td>
                            {{-- Foto Keluar --}}
                            <td class="p-2 border-r border-gray-300 whitespace-nowrap">
                                <div class="flex justify-center">
                                    <img src="{{ $attendance->photo_check_out ? asset('storage/' . $attendance->photo_check_out) : asset('img/application-logo.svg') }}"
                                        alt="Foto Check Out" class="w-10 h-10 object-contain">
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">Tidak ada data absensi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</x-app-layout>
