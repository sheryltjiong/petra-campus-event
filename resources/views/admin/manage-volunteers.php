{{-- resources/views/admin/manage-volunteers.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-7xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelola Volunteer</h1>
            <p class="text-gray-600">{{ $event->name }}</p>
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} • {{ $event->location }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.volunteer-positions', $event->id) }}" 
               class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                Kelola Posisi
            </a>
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Event Status Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $volunteerPositions->count() }}</div>
                <div class="text-sm text-gray-600">Total Posisi</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ $volunteerPositions->sum(function($pos) { return $pos->volunteerApplications->where('status', 'approved')->count(); }) }}
                </div>
                <div class="text-sm text-gray-600">Volunteer Diterima</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">
                    {{ $volunteerPositions->sum(function($pos) { return $pos->volunteerApplications->where('status', 'pending')->count(); }) }}
                </div>
                <div class="text-sm text-gray-600">Menunggu Review</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-600">{{ $volunteerPositions->sum('quota') }}</div>
                <div class="text-sm text-gray-600">Total Kuota</div>
            </div>
        </div>
    </div>

    <!-- Volunteer Positions -->
    @forelse($volunteerPositions as $position)
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">{{ $position->position_name }}</h3>
                        <p class="text-gray-600 mt-1">{{ $position->description }}</p>
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                            <span>Kuota: {{ $position->quota }}</span>
                            <span>Deadline: {{ \Carbon\Carbon::parse($position->deadline)->format('d M Y') }}</span>
                            <span>Pendaftar: {{ $position->volunteerApplications->count() }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        @php
                            $approved = $position->volunteerApplications->where('status', 'approved')->count();
                            $pending = $position->volunteerApplications->where('status', 'pending')->count();
                            $remaining = $position->quota - $approved;
                        @endphp
                        <div class="text-lg font-semibold {{ $remaining > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $remaining }} slot tersisa
                        </div>
                        @if($pending > 0)
                            <div class="text-sm text-yellow-600">{{ $pending }} menunggu review</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Applications List -->
            @if($position->volunteerApplications->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($position->volunteerApplications as $application)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $application->user->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $application->user->nrp ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $application->user->jurusan ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($application->status === 'approved')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Diterima
                                            </span>
                                        @elseif($application->status === 'rejected')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $application->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                        @if($application->status === 'pending')
                                            <form method="POST" action="{{ route('admin.approve-volunteer', $application->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition text-xs">
                                                    Terima
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.reject-volunteer', $application->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-xs">
                                                    Tolak
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs">
                                                {{ $application->status === 'approved' ? 'Sudah Diterima' : 'Sudah Ditolak' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p>Belum ada yang mendaftar untuk posisi ini</p>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-800 mb-2">Belum Ada Posisi Volunteer</h3>
            <p class="text-gray-600 mb-4">Mulai dengan membuat posisi volunteer untuk event ini.</p>
            <a href="{{ route('admin.volunteer-positions', $event->id) }}" 
               class="inline-block px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition">
                Buat Posisi Volunteer
            </a>
        </div>
    @endforelse

    <!-- Quick Actions -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.volunteer-positions', $event->id) }}" 
               class="block p-4 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                <div class="font-medium text-blue-900">Kelola Posisi</div>
                <div class="text-sm text-blue-700">Tambah atau edit posisi volunteer</div>
            </a>
            <a href="{{ route('admin.participants', $event->id) }}" 
               class="block p-4 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                <div class="font-medium text-blue-900">Kelola Peserta</div>
                <div class="text-sm text-blue-700">Lihat dan kelola pendaftar peserta</div>
            </a>
            <a href="{{ route('events.show', $event->id) }}" 
               class="block p-4 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                <div class="font-medium text-blue-900">Lihat Event</div>
                <div class="text-sm text-blue-700">Lihat halaman publik event</div>
            </a>
        </div>
    </div>
</div>
@endsection