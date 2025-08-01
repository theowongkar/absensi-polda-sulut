<x-app-layout>

    {{-- Judul Halaman --}}
    <x-slot name="title">Edit Data Pegawai</x-slot>

    {{-- Bagian Tambah Pegawai --}}
    <section class="space-y-2">

        {{-- Flash Message --}}
        <x-alerts.flash-message />

        {{-- Form Tambah Pegawai --}}
        <div class="p-4 bg-white rounded-lg border border-gray-300 shadow">
            <form action="{{ route('dashboard.employee.update', $employee->nrp) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-5">
                    {{-- Data Pegawai --}}
                    <div>
                        <h2 class="mb-5 font-semibold text-gray-700">Data Pegawai</h2>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="md:col-span-2" x-data="{
                                preview: '',
                                placeholder: '{{ $employee->avatar ? asset('storage/' . $employee->avatar) : asset('img/placeholder-profile.webp') }}'
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
                                        @error('avatar')
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <x-forms.input name="nrp" label="NRP" type="number"
                                :value="old('nrp', $employee->nrp)"></x-forms.input>
                            <x-forms.input name="name" label="Nama" :value="old('name', $employee->name)"></x-forms.input>
                            <x-forms.select name="gender" label="Jenis Kelamin" :options="['Laki-laki', 'Perempuan']"
                                :selected="old('gender', $employee->gender)"></x-forms.select>
                            <x-forms.input name="position" label="Jabatan" :value="old('position', $employee->position)"></x-forms.input>
                            <x-forms.input name="phone" label="No. Telepon (Opsional)"
                                :value="old('phone', $employee->phone)"></x-forms.input>
                            <x-forms.input name="date_of_birth" label="Tanggal Lahir" type="date"
                                :value="old('date_of_birth', $employee->date_of_birth->format('Y-m-d'))"></x-forms.input>
                            <div class="md:col-span-2">
                                <x-forms.select name="status" label="Status Pegawai" :options="['Aktif', 'Pensiun', 'Meninggal Dunia', 'Diberhentikan']"
                                    :selected="old('status', $employee->status)"></x-forms.select>
                            </div>
                            <x-forms.textarea name="address" label="Alamat" :value="old('address', $employee->address)"></x-forms.textarea>
                        </div>
                    </div>

                    {{-- Data Pengguna --}}
                    <div>
                        <h2 class="mb-5 font-semibold text-gray-700">Data Pengguna (Login)</h2>

                        <div class="grid grid-cols-1 gap-4">
                            <x-forms.input name="username" label="Username" :value="old('username', $employee->user->name)"></x-forms.input>
                            <x-forms.input name="email" label="Email" type="email"
                                :value="old('email', $employee->user->email)"></x-forms.input>
                            <x-forms.select name="role" label="Role Pengguna" :options="['Admin', 'Pengguna']"
                                :selected="old('role', $employee->user->role)"></x-forms.select>
                            <x-forms.input name="password" label="Password (Kosongkan jika tidak ingin diubah)"
                                type="password"></x-forms.input>
                            <x-forms.input name="password_confirmation" label="Konfirmasi Password"
                                type="password"></x-forms.input>
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
