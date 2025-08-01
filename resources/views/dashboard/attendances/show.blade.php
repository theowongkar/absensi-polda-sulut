<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Presensi {{ $employee->name }}</x-slot>

    <section class="space-y-2">
        {{-- Header Card --}}
        <div class="bg-gray-50 rounded-lg border border-gray-300 shadow">
            <div class="p-2 space-y-2">
                <div class="flex flex-col lg:flex-row items-center justify-end gap-4">

                    {{-- Tombol Tambah Presensi --}}
                    <div x-data="{ createModal: false }">
                        <x-buttons.primary-button @click="createModal = true"
                            class="w-full lg:w-auto text-center bg-green-600 hover:bg-green-700">
                            Tambah Presensi
                        </x-buttons.primary-button>

                        {{-- Modal Tambah --}}
                        <div x-show="createModal" x-cloak
                            class="fixed inset-0 flex items-center justify-center bg-black/50 z-10">
                            <div class="w-full max-w-md bg-white rounded-lg shadow p-4">
                                <h2 class="mb-5 font-semibold text-gray-700">Tambah Presensi</h2>

                                <form action="{{ route('dashboard.attendance.store', $employee->nrp) }}" method="POST">
                                    @csrf

                                    <div class="grid gap-4">
                                        {{-- Tanggal --}}
                                        <x-forms.input name="date" label="Tanggal" type="date"></x-forms.input>
                                        {{-- Jam Masuk --}}
                                        <x-forms.input name="check_in" label="Jam Masuk" type="time"></x-forms.input>
                                        {{-- Jam Keluar --}}
                                        <x-forms.input name="check_out" label="Jam Keluar"
                                            type="time"></x-forms.input>
                                    </div>
                                    <div class="flex justify-end gap-2 mt-4">
                                        <x-buttons.primary-button type="button" @click="createModal = false"
                                            class="bg-gray-600 hover:bg-gray-700">Kembali</x-buttons.primary-button>
                                        <x-buttons.primary-button type="submit"
                                            class="bg-green-600 hover:bg-green-700">Simpan</x-buttons.primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                        <th class="p-2 font-normal text-center">Aksi</th>
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
                            {{-- Aksi Edit --}}
                            <td class="p-2 whitespace-nowrap">
                                <div class="flex justify-center items-center gap-2">
                                    <div x-data="{ editModal: false }">
                                        <button @click="editModal = true"
                                            class="text-yellow-600 hover:underline text-sm cursor-pointer">Edit</button>

                                        {{-- Modal Edit --}}
                                        <div x-show="editModal" x-cloak
                                            class="fixed inset-0 flex items-center justify-center bg-black/50 z-20">
                                            <div class="w-full max-w-md bg-white rounded-lg shadow p-4">
                                                <h2 class="mb-5 text-base font-semibold text-gray-700">Edit Presensi
                                                </h2>

                                                <form
                                                    action="{{ route('dashboard.attendance.update', [$employee->nrp, $attendance->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="grid gap-4">
                                                        {{-- Tanggal --}}
                                                        <x-forms.input name="date" label="Tanggal" type="date"
                                                            :value="old('date', $attendance->date->format('Y-m-d'))"></x-forms.input>
                                                        {{-- Jam Masuk --}}
                                                        <x-forms.input name="check_in" label="Jam Masuk" type="time"
                                                            :value="old(
                                                                'check_in',
                                                                substr($attendance->check_in, 0, 5),
                                                            )"></x-forms.input>
                                                        {{-- Jam Keluar --}}
                                                        <x-forms.input name="check_out" label="Jam Keluar"
                                                            type="time" :value="old(
                                                                'check_out',
                                                                substr($attendance->check_out, 0, 5),
                                                            )"></x-forms.input>
                                                    </div>
                                                    <div class="flex justify-end gap-2 mt-4">
                                                        <x-buttons.primary-button type="button"
                                                            @click="editModal = false"
                                                            class="bg-gray-600 hover:bg-gray-700">Kembali</x-buttons.primary-button>
                                                        <x-buttons.primary-button
                                                            type="submit">Simpan</x-buttons.primary-button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <form
                                        action="{{ route('dashboard.attendance.destroy', [$employee->nrp, $attendance->id]) }}"
                                        method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="text-red-600 hover:underline text-sm cursor-pointer">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">Tidak ada data absensi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</x-app-layout>
