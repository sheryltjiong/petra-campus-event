{{-- resources/views/admin/edit-volunteer-position.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-3xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Posisi Volunteer</h1>
        <p class="text-sm text-gray-600">{{ $event->name }} &mdash; {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
    </div>

    <!-- Edit Form -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route('admin.update-volunteer-position', $position->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="position_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Posisi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="position_name" id="position_name" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500"
                       value="{{ old('position_name', $position->position_name) }}">
                @error('position_name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="initial_quota" class="block text-sm font-medium text-gray-700 mb-1">
                    Kuota <span class="text-red-500">*</span>
                </label>
                <input type="number" name="initial_quota" id="initial_quota" required min="1"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500"
                       value="{{ old('initial_quota', $position->initial_quota) }}">
                @error('initial_quota')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Job Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500">{{ old('description', $position->description) }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">
                    Deadline Pendaftaran <span class="text-red-500">*</span>
                </label>
                <input type="datetime-local" name="deadline" id="deadline" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500"
                       value="{{ old('deadline', \Carbon\Carbon::parse($position->deadline)->format('Y-m-d\TH:i')) }}">
                @error('deadline')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin.volunteer-positions', $event->id) }}"
                   class="inline-block px-5 py-3 text-sm bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </a>
                <button type="submit"
                        class="inline-block px-6 py-3 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
