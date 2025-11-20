{{-- resources/views/admin/volunteer-positions.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-7xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelola Posisi Volunteer</h1>
                <p class="text-gray-600">{{ $event->name }}</p>
                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} •
                    {{ $event->location }}
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.manage-volunteers', $event->id) }}"
                    class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                    Lihat Aplikasi
                </a>
                <a href="{{ route('admin.dashboard') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Create New Position -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Tambah Posisi Volunteer Baru</h2>

            <form method="POST" action="{{ route('admin.create-volunteer-position', $event->id) }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="position_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Posisi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="position_name" id="position_name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Koordinator Acara" value="{{ old('position_name') }}">
                        @error('position_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="initial_quota" class="block text-sm font-medium text-gray-700 mb-2">
                            Kuota <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="initial_quota" id="initial_quota" required min="1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: 3" value="{{ old('initial_quota') }}">
                        @error('initial_quota')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Job Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Deskripsi tugas dan tanggung jawab posisi ini...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">
                        Deadline Pendaftaran <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="deadline" id="deadline" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="{{ old('deadline') }}">
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                        Tambah Posisi
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Positions -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Posisi Volunteer Tersedia</h2>
            </div>

            @if($volunteerPositions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Posisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deadline</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pendaftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($volunteerPositions as $position)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $position->position_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 max-w-xs">
                                            {{ Str::limit($position->description, 100) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $position->initial_quota }} orang
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($position->deadline)->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $position->volunteerApplications->count() }} pendaftar
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        <a href="{{ route('admin.manage-volunteers', $event->id) }}"
                                            class="text-blue-600 hover:text-blue-900">Lihat Aplikasi</a>
                                        <a href="{{ route('admin.edit-volunteer-position', $position->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                        <form method="POST" action="{{ route('admin.delete-volunteer-position', $position->id) }}"
                                            class="inline" onsubmit="return confirm('Yakin ingin menghapus posisi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Belum Ada Posisi Volunteer</h3>
                    <p class="text-gray-600">Mulai dengan menambahkan posisi volunteer pertama menggunakan form di atas.</p>
                </div>
            @endif
        </div>

        <!-- Guidelines -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Tips Mengelola Posisi Volunteer</h3>
            <ul class="text-blue-800 text-sm space-y-2">
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Buat nama posisi yang jelas dan mudah dipahami
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Tulis job description yang detail agar volunteer tahu apa yang diharapkan
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Tentukan kuota yang realistis sesuai kebutuhan event
                </li>
                <li class="flex items-start">
                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                    Set deadline yang memberikan waktu cukup untuk recruitment
                </li>
            </ul>
        </div>
    </div>
@endsection