<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Tambah Data Pegawai</x-slot>

    {{-- Bagian Tambah Pegawai --}}
    <section class="space-y-2">

        {{-- Flash Message --}}
        <x-alerts.flash-message />

        {{-- Form Tambah Pegawai --}}
        <div class="p-4 bg-white rounded-lg border border-gray-300 shadow">
            <form action="{{ route('dashboard.employee.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid md:grid-cols-2 gap-5">
                    {{-- Data Pegawai --}}
                    <div>
                        <h2 class="mb-5 font-semibold text-gray-700">Data Pegawai</h2>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="md:col-span-2" x-data="{
                                preview: '',
                                placeholder: '{{ asset('img/placeholder-profile.webp') }}'
                            }">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border border-gray-300">
                                        <img :src="preview || placeholder" alt="Preview"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="relative">
                                        <input type="file" name="avatar" id="avatar"
                                            accept="image/png,image/jpeg" class="absolute inset-0 opacity-0"
                                            @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : ''">

                                        <button type="button"
                                            class="px-3 py-2 bg-indigo-50 text-indigo-700 text-sm rounded-md border border-gray-300 cursor-pointer">
                                            Pilih File
                                        </button>
                                        <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Max 2MB.</p>
                                    </div>
                                </div>
                            </div>
                            <x-forms.input name="nrp" label="NRP" type="number"></x-forms.input>
                            <x-forms.input name="name" label="Nama"></x-forms.input>
                            <x-forms.select name="gender" label="Jenis Kelamin" :options="['Laki-laki', 'Perempuan']"></x-forms.select>
                            <x-forms.input name="position" label="Jabatan"></x-forms.input>
                            <x-forms.input name="phone" label="No. Telepon (Opsional)"></x-forms.input>
                            <x-forms.input name="date_of_birth" label="Tanggal Lahir" type="date"></x-forms.input>
                            <x-forms.textarea name="address" label="Alamat"></x-forms.textarea>
                        </div>
                    </div>

                    {{-- Data User --}}
                    <div>
                        <h2 class="mb-5 font-semibold text-gray-700">Data User (Login)</h2>

                        <div class="grid grid-cols-1 gap-4">
                            <x-forms.input name="username" label="Username"></x-forms.input>
                            <x-forms.input name="email" label="Email" type="email"></x-forms.input>
                            <x-forms.select name="role" label="Role Pengguna" :options="['Admin', 'Pengguna']"></x-forms.select>
                            <x-forms.input name="password" label="Password" value="poldasulut123"></x-forms.input>
                            <x-forms.input name="password_confirmation" label="Konfirmasi Password"
                                value="poldasulut123"></x-forms.input>
                        </div>
                    </div>
                </div>

                {{-- Button --}}
                <div class="mt-4 flex justify-end gap-2">
                    <x-buttons.primary-button href="{{ route('dashboard.employee.index') }}"
                        class="bg-gray-600 hover:bg-gray-700">Kembali</x-buttons.primary-button>
                    <x-buttons.primary-button type="submit">Simpan</x-buttons.primary-button>
                </div>
            </form>
        </div>
    </section>

</x-app-layout>
