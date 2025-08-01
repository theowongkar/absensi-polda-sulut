<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Profil Saya</x-slot>

    {{-- Bagian Profil Saya --}}
    <section class="space-y-5">

        {{-- Kartu Pegawai --}}
        <div class="max-w-lg mx-auto bg-white rounded-lg border border-gray-300 shadow overflow-hidden">
            {{-- Header --}}
            <div class="text-center py-3 bg-[#141652] border-b border-blue-300">
                <h2 class="text-xs md:text-base text-white font-bold">REPUBLIK INDONESIA</h2>
                <p class="text-[0.65rem] md:text-sm text-gray-300 font-semibold">KARTU TANDA PEGAWAI</p>
            </div>

            {{-- Body --}}
            <div class="flex justify-between p-4">
                <div class="space-y-1 text-[0.6rem] md:text-sm">
                    <div class="grid grid-cols-[5.5rem_5px_1fr] md:grid-cols-[8.2rem_5px_1fr]">
                        <div class="font-semibold text-gray-700">NIP</div>
                        <div>:</div>
                        <div class="font-normal">{{ $user->employee->nrp }}</div>
                    </div>

                    <div class="grid grid-cols-[5.5rem_5px_1fr] md:grid-cols-[8.2rem_5px_1fr]">
                        <div class="font-semibold text-gray-700">Nama</div>
                        <div>:</div>
                        <div class="font-normal">{{ $user->employee->name }}</div>
                    </div>

                    <div class="grid grid-cols-[5.5rem_5px_1fr] md:grid-cols-[8.2rem_5px_1fr]">
                        <div class="font-semibold text-gray-700">Tgl Lahir</div>
                        <div>:</div>
                        <div class="font-normal">
                            {{ \Carbon\Carbon::parse($user->employee->date_of_birth)->format('d-m-Y') }}
                        </div>
                    </div>

                    <div class="grid grid-cols-[5.5rem_5px_1fr] md:grid-cols-[8.2rem_5px_1fr]">
                        <div class="font-semibold text-gray-700">Jenis Kelamin</div>
                        <div>:</div>
                        <div class="font-normal">{{ $user->employee->gender }}</div>
                    </div>

                    <div class="grid grid-cols-[5.5rem_5px_1fr] md:grid-cols-[8.2rem_5px_1fr]">
                        <div class="font-semibold text-gray-700">Jabatan</div>
                        <div>:</div>
                        <div class="font-normal">{{ $user->employee->position }}</div>
                    </div>

                    <div class="grid grid-cols-[5.5rem_5px_1fr] md:grid-cols-[8.2rem_5px_1fr]">
                        <div class="font-semibold text-gray-700">No. Telp</div>
                        <div>:</div>
                        <div class="font-normal">{{ $user->employee->phone ?? '' }}</div>
                    </div>

                    <div class="grid grid-cols-[5.5rem_5px_1fr] md:grid-cols-[8.2rem_5px_1fr]">
                        <div class="font-semibold text-gray-700">Alamat</div>
                        <div>:</div>
                        <div class="font-normal">{{ $user->employee->address }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div
                        class="w-16 h-24 md:w-24 md:h-32 bg-gray-100 border border-gray-300 overflow-hidden flex-shrink-0">
                        <img src="{{ $user->employee->avatar
                            ? asset('storage/' . $user->employee->avatar)
                            : asset('img/placeholder-profile.webp') }}"
                            alt="Foto Profil" class="w-full h-full object-cover">
                    </div>

                    <div class="flex items-center justify-center">
                        @php
                            $status = $user->employee->status;
                            $colors = [
                                'Aktif' => 'bg-green-200 text-green-800 border border-green-400',
                                'Pensiun' => 'bg-blue-200 text-blue-800 border border-blue-400',
                                'Meninggal Dunia' => 'bg-yellow-200 text-yellow-800 border border-yellow-400',
                                'Diberhentikan' => 'bg-red-200 text-red-800 border border-red-400',
                            ];
                        @endphp
                        <span
                            class="px-2 rounded-full text-[0.55rem] md:text-xs font-medium whitespace-nowrap {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">{{ $status }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Message --}}
        <x-alerts.flash-message />

        {{-- Card Pengaturan User --}}
        <div class="p-4 bg-white rounded-lg border border-gray-300 shadow">
            <h2 class="mb-5 font-semibold text-gray-700">Data Pengguna (Login)</h2>

            {{-- Form Ubah Profil --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div x-data="{
                        preview: '',
                        placeholder: '{{ $user->employee->avatar ? asset('storage/' . $user->employee->avatar) : asset('img/placeholder-profile.webp') }}'
                    }">
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-300">
                                <img :src="preview || placeholder" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <div class="relative">
                                <input type="file" name="avatar" id="avatar" accept="image/png,image/jpeg"
                                    class="absolute inset-0 opacity-0"
                                    @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''">

                                <button type="button"
                                    class="px-3 py-2 bg-indigo-50 text-indigo-700 text-sm rounded-md border border-gray-300 cursor-pointer">
                                    Pilih File
                                </button>
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Max 2MB.</p>
                                @error('avatar')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <x-forms.input name="username" label="Username" :value="old('username', $user->name)"></x-forms.input>
                    <x-forms.input name="email" label="Email" type="email" :value="old('email', $user->email)"></x-forms.input>
                    <div>
                        <label for="role" class="block mb-1 text-sm font-medium text-gray-700">
                            Role Pengguna
                        </label>
                        <input type="text" name="role" value="{{ $user->role }}" disabled
                            class="w-full px-3 py-2 text-sm bg-gray-200 border border-gray-300 rounded-md focus:outline-none focus:ring-1">
                    </div>
                    <x-forms.input name="password" label="Password (Kosongkan jika tidak ingin diubah)"
                        type="password"></x-forms.input>
                    <x-forms.input name="password_confirmation" label="Konfirmasi Password"
                        type="password"></x-forms.input>
                </div>

                <div class="mt-4 flex justify-end">
                    <x-buttons.primary-button type="submit">Simpan</x-buttons.primary-button>
                </div>
            </form>
        </div>
    </section>

</x-app-layout>
