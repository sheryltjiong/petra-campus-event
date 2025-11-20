@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-4xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Event</h1>
            <p class="text-gray-600">Perbarui informasi event yang sudah dibuat</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.events.update', $event->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Event Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-3 border rounded-lg"
                        value="{{ old('name', $event->name) }}">
                    @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" required
                        class="w-full px-4 py-3 border rounded-lg">{{ old('description', $event->description) }}</textarea>
                    @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Date & Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="date" id="date" required
                            class="w-full px-4 py-3 border rounded-lg"
                            value="{{ old('date', $event->date) }}">
                        @error('date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-2">Jam</label>
                        <input type="time" name="time" id="time" required
                            class="w-full px-4 py-3 border rounded-lg"
                            value="{{ old('time', $event->time) }}">
                        @error('time') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Location & Slot -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <input type="text" name="location" id="location" required
                            class="w-full px-4 py-3 border rounded-lg"
                            value="{{ old('location', $event->location) }}">
                        @error('location') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="slot_peserta" class="block text-sm font-medium text-gray-700 mb-2">Slot Peserta</label>
                        <input type="number" name="slot_peserta" id="slot_peserta" min="1" required
                            class="w-full px-4 py-3 border rounded-lg"
                            value="{{ old('slot_peserta', $event->slot_peserta) }}">
                        @error('slot_peserta') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- SKKK -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="skkk_points" class="block text-sm font-medium text-gray-700 mb-2">SKKK Points</label>
                        <input type="number" name="skkk_points" id="skkk_points" min="0"
                            class="w-full px-4 py-3 border rounded-lg"
                            value="{{ old('skkk_points', $event->skkk_points) }}">
                        @error('skkk_points') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="skkk_category" class="block text-sm font-medium text-gray-700 mb-2">Kategori SKKK</label>
                        <select name="skkk_category" id="skkk_category"
                            class="w-full px-4 py-3 border rounded-lg">
                            <option value="">Pilih Kategori</option>
                            <option value="A" {{ old('skkk_category', $event->skkk_category) == 'A' ? 'selected' : '' }}>Penalaran</option>
                            <option value="B" {{ old('skkk_category', $event->skkk_category) == 'B' ? 'selected' : '' }}>Bakat Minat</option>
                            <option value="C" {{ old('skkk_category', $event->skkk_category) == 'C' ? 'selected' : '' }}>Organisasi & Kepemimpinan</option>
                            <option value="D" {{ old('skkk_category', $event->skkk_category) == 'D' ? 'selected' : '' }}>Pengabdian Masyarakat</option>
                        </select>
                        @error('skkk_category') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Assign Admins -->
                <div>
                    <label for="admin_ids" class="block text-sm font-medium text-gray-700 mb-2">Admin Penanggung Jawab</label>
                    <select name="admin_ids[]" id="admin_ids" multiple required
                        class="w-full px-4 py-3 border rounded-lg">
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}"
                                {{ in_array($admin->id, old('admin_ids', $event->users->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $admin->name }} ({{ $admin->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('admin_ids') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end pt-6">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-6 py-3 border text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="ml-4 px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
