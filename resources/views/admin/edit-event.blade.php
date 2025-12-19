{{-- resources/views/admin/edit-event.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-4xl">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Event</h1>
            <p class="text-gray-600">Perbarui informasi event <strong>{{ $event->name }}</strong></p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" 
                  action="{{ route('admin.events.update', $event->id) }}" 
                  class="space-y-6" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan judul event" 
                        value="{{ old('name', $event->name) }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Masukkan deskripsi event">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Poster Event --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Poster Event
                    </label>
                    
                    {{-- Preview Gambar Saat Ini --}}
                    @if($event->image)
                        <div class="mb-3">
                            <p class="text-xs text-gray-500 mb-1">Poster saat ini:</p>
                            <img src="{{ asset('storage/' . $event->image) }}" 
                                 class="w-32 h-auto rounded-lg shadow-sm border border-gray-200" alt="Current Poster">
                        </div>
                    @endif

                    <input type="file" name="image" id="image" accept="image/*"
                        class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-3 file:px-4
                        file:rounded-l-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                        border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah poster. (Format: JPG, JPEG, PNG. Max 2MB)</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal & Jam --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            value="{{ old('date', $event->date) }}">
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
                            value="{{ old('time', $event->time) }}">
                        @error('time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Lokasi & Slot --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="location" id="location" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan lokasi event" 
                            value="{{ old('location', $event->location) }}">
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
                            placeholder="Masukkan jumlah slot" 
                            value="{{ old('slot_peserta', $event->slot_peserta) }}">
                        @error('slot_peserta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- SKKK Section --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="skkk_points" class="block text-sm font-medium text-gray-700 mb-2">
                            SKKK Points
                        </label>
                        <input type="number" name="skkk_points" id="skkk_points" min="0" step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: 10" 
                            value="{{ old('skkk_points', $event->skkk_points) }}">
                    </div>

                    <div>
                        <label for="skkk_category" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori SKKK
                        </label>
                        <select name="skkk_category" id="skkk_category"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Kategori</option>
                            @foreach(['A'=>'Penalaran','B'=>'Bakat Minat','C'=>'Organisasi & Kepemimpinan','D'=>'Pengabdian Masyarakat'] as $key => $label)
                                <option value="{{ $key }}" 
                                    {{ old('skkk_category', $event->skkk_category) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- SKKK Volunteer --}}
                <div>
                    <label for="skkk_points_volunteer" class="block text-sm font-medium text-gray-700 mb-2">
                        SKKK Volunteer
                    </label>
                    <input type="number" name="skkk_points_volunteer" id="skkk_points_volunteer" min="0" step="0.01"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Poin untuk panitia" 
                        value="{{ old('skkk_points_volunteer', $event->skkk_points_volunteer) }}">
                </div>

                {{-- Admin Penanggung Jawab --}}
                <div>
                    <label for="admin_ids" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Penanggung Jawab <span class="text-red-500">*</span>
                    </label>
                    <select name="admin_ids[]" id="admin_ids" multiple required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}"
                                {{ in_array($admin->id, old('admin_ids', $event->users->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $admin->name }} ({{ $admin->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Tekan <strong>Ctrl</strong> (Windows) atau <strong>Cmd</strong> (Mac) untuk memilih lebih dari satu admin.</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

        {{-- Info Box --}}
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Panduan Mengedit Event</h3>
            <ul class="text-blue-800 text-sm space-y-2">
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Perubahan data akan langsung terlihat oleh mahasiswa setelah disimpan.
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Jika slot peserta dikurangi di bawah jumlah pendaftar saat ini, sistem tidak akan menghapus pendaftar lama secara otomatis.
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Pastikan menghubungi admin lain jika Anda mengubah penanggung jawab acara.
                </li>
            </ul>
        </div>
    </div>
@endsection