{{-- resources/views/admin/edit-event.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-4xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Event</h1>
        <p class="text-gray-600">Perbarui informasi event yang sudah dibuat</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST"
              action="{{ route('admin.events.update', $event->id) }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Judul <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    value="{{ old('name', $event->name) }}">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="4" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Poster Event --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Poster Event
                </label>
                <input type="file" name="image" accept="image/*"
                    class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg">
                <p class="text-xs text-gray-500 mt-1">
                    Kosongkan jika tidak ingin mengganti poster
                </p>

                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}"
                         class="mt-3 w-32 rounded shadow">
                @endif

                @error('image')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal & Jam --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        value="{{ old('date', $event->date) }}">
                    @error('date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="time" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        value="{{ old('time', $event->time) }}">
                    @error('time')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Lokasi & Slot --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        value="{{ old('location', $event->location) }}">
                    @error('location')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Slot Peserta <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="slot_peserta" min="1" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        value="{{ old('slot_peserta', $event->slot_peserta) }}">
                    @error('slot_peserta')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- SKKK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        SKKK Points
                    </label>
                    <input type="number" name="skkk_points" min="0" step="0.01"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg"
                        value="{{ old('skkk_points', $event->skkk_points) }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori SKKK
                    </label>
                    <select name="skkk_category"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    SKKK Volunteer
                </label>
                <input type="number" name="skkk_points_volunteer" min="0" step="0.01"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg"
                    value="{{ old('skkk_points_volunteer', $event->skkk_points_volunteer) }}">
            </div>

            {{-- Admin --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Admin Penanggung Jawab <span class="text-red-500">*</span>
                </label>
                <select name="admin_ids[]" multiple required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}"
                            {{ in_array($admin->id, old('admin_ids', $event->users->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $admin->name }} ({{ $admin->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end pt-6 border-t">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-6 py-3 border rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="ml-4 px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
