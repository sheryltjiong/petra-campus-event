{{-- resources/views/admin/create-event.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Buat Event</h1>
            <p class="text-gray-600">Buat event baru untuk mahasiswa Petra Christian University</p>
        </div>

        <!-- Create Event Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.create-event') }}" class="space-y-6">
                @csrf

                <!-- Event Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan judul event" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan deskripsi event">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('date') }}">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                            Jam <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="time" id="time" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('time') }}">
                        @error('time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location & Slot Peserta-->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="location" id="location" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan lokasi event" value="{{ old('location') }}">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slot_peserta" class="block text-sm font-medium text-gray-700 mb-2">
                            Slot Peserta <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="slot_peserta" id="slot_peserta" required min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan jumlah slot peserta" value="{{ old('slot_peserta') }}">
                        @error('slot_peserta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SKKK Points & Kategori SKKK-->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="skkk_points" class="block text-sm font-medium text-gray-700 mb-2">
                            SKKK Points
                        </label>
                        <input type="number" name="skkk_points" id="skkk_points" min="0" step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan poin SKKK (opsional)" value="{{ old('skkk_points') }}">
                        @error('skkk_points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="skkk_category" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori SKKK
                        </label>
                        <select name="skkk_category" id="skkk_category"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="" selected>Pilih Kategori (optional)</option>
                            <option value="A" {{ old('skkk_category') == 'Penalaran' ? 'selected' : '' }}>Penalaran</option>
                            <option value="B" {{ old('skkk_category') == 'Bakat Minat' ? 'selected' : '' }}>Bakat Minat</option>
                            <option value="C" {{ old('skkk_category') == 'Organisasi & Kepemimpinan' ? 'selected' : '' }}>Organisasi & Kepemimpinan</option>
                            <option value="D" {{ old('skkk_category') == 'Pengabdian Masyarakat' ? 'selected' : '' }}>Pengabdian Masyarakat</option>
                        </select>
                        @error('skkk_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- SKKK Points untuk Panitia -->
<div>
    <label for="skkk_points_volunteer" class="block text-sm font-medium text-gray-700 mb-2">
        SKKK Volunteer
    </label>
    <input type="number" name="skkk_points_volunteer" id="skkk_points_volunteer" min="0" step="0.01"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        placeholder="Masukkan poin SKKK untuk panitia" value="{{ old('skkk_points_volunteer') }}">
    @error('skkk_points_volunteer')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

                <!-- Assign Admin -->
                <div>
                    <label for="admin_ids" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Admin Penanggung Jawab <span class="text-red-500">*</span>
                    </label>
                    <select name="admin_ids[]" id="admin_ids" multiple required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @forelse($admins as $admin)
                            <option value="{{ $admin->id }}" {{ collect(old('admin_ids'))->contains($admin->id) ? 'selected' : '' }}>
                                {{ $admin->name }} ({{ $admin->email }})
                            </option>
                        @empty
                            <option disabled>Tidak ada admin tersedia</option>
                        @endforelse
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Tekan <strong>Ctrl</strong> (Windows) atau <strong>Cmd</strong>
                        (Mac) untuk memilih lebih dari satu admin.</p>
                    @error('admin_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>



                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                        Buat Event
                    </button>
                </div>
            </form>
        </div>

        <!-- Event Creation Guidelines -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Panduan Membuat Event</h3>
            <ul class="text-blue-800 text-sm space-y-2">
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Event baru akan dibuat dengan status "Pendaftaran Volunteer Terbuka"
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Anda dapat menambahkan posisi volunteer setelah event dibuat
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Status pendaftaran dapat diubah melalui dashboard admin
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Pastikan semua informasi sudah benar sebelum menyimpan
                </li>
            </ul>
        </div>
    </div>
@endsection