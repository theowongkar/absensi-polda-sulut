<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Presensi Saya</x-slot>

    {{-- Bagian Input Presensi --}}
    <section class="space-y-2">
        {{-- Info Pegawai --}}
        <div class="bg-gray-50 rounded-lg border p-3">
            <p>Nama: {{ $employee->name }}</p>
            <p>NRP: {{ $employee->nrp }}</p>
        </div>

        <x-alerts.flash-message />

        {{-- Form Presensi --}}
        <div class="w-full max-w-md mx-auto">
            <div class="bg-white p-5 rounded-lg border shadow">
                <h3 class="text-2xl font-semibold border-b mb-3">Presensi Hari Ini</h3>

                <form action="{{ route('dashboard.my-attendance.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <div x-data="{ preview: null }">
                        <label for="photo" class="block mb-1 text-sm font-medium text-gray-700">Ambil Foto
                            Selfie</label>
                        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png"
                            capture="user"
                            class="w-full text-sm bg-white border rounded-md focus:outline-none focus:ring-1 file:bg-black file:text-white file:mr-2 file:p-2 hover:file:bg-gray-800"
                            @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">

                        <template x-if="preview">
                            <div class="mt-3 max-h-64 overflow-auto border rounded">
                                <img :src="preview" class="w-full object-contain">
                            </div>
                        </template>

                        @error('photo')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-center">
                        @if (!$todayAttendance)
                            <x-buttons.primary-button type="submit" class="bg-green-600 hover:bg-green-700">Check
                                In</x-buttons.primary-button>
                        @elseif($todayAttendance && !$todayAttendance->check_out)
                            <x-buttons.primary-button type="submit" class="bg-blue-600 hover:bg-blue-700">Check
                                Out</x-buttons.primary-button>
                        @else
                            <p class="text-gray-500">Anda sudah melakukan check-in & check-out hari ini.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

</x-app-layout>
