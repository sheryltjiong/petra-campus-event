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
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $volunteerPositions->count() }}</div>
                <div class="text-sm text-gray-600">Total Posisi</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ $volunteerPositions->sum(function($pos) { return $pos->volunteerApplications->where('status', 'accepted')->count(); }) }}
                </div>
                <div class="text-sm text-gray-600">Volunteer Diterima</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">
                    {{ $volunteerPositions->sum(function($pos) { return $pos->volunteerApplications->where('status', 'interview_scheduled')->count(); }) }}
                </div>
                <div class="text-sm text-gray-600">Interview Dijadwalkan</div>
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
        <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
            <!-- Position Header -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $position->position_name }}</h3>
                        <p class="text-gray-600 mb-3">{{ $position->description }}</p>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Kuota: {{ $position->initial_quota }}
                            </span>
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Deadline: {{ \Carbon\Carbon::parse($position->deadline)->format('d M Y') }}
                            </span>
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Pendaftar: {{ $position->volunteerApplications->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 lg:mt-0 lg:ml-6 lg:text-right">
                        @php
                            $accepted = $position->volunteerApplications->where('status', 'accepted')->count();
                            $pending = $position->volunteerApplications->where('status', 'pending')->count();
                            $interview = $position->volunteerApplications->where('status', 'interview_scheduled')->count();
                            $remaining = $position->initial_quota - $accepted;
                        @endphp
                        <div class="text-lg font-semibold mb-1 {{ $remaining > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $remaining }} slot tersisa
                        </div>
                        <div class="flex flex-col space-y-1">
                            @if($pending > 0)
                                <span class="inline-flex items-center px-2 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">
                                    {{ $pending }} pending review
                                </span>
                            @endif
                            @if($interview > 0)
                                <span class="inline-flex items-center px-2 py-1 text-sm bg-blue-100 text-blue-800 rounded-full">
                                    {{ $interview }} interview scheduled
                                </span>
                            @endif
                        </div>
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
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-600">
                                                        {{ substr($application->user->name ?? 'N', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $application->user->name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $application->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $application->user->nrp ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $application->user->jurusan ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($application->status === 'accepted')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Diterima
                                            </span>
                                        @elseif($application->status === 'interview_scheduled')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Interview Dijadwalkan
                                            </span>
                                        @elseif($application->status === 'rejected')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Pending
                                            </span>
                                        @endif
                                        
                                        <!-- Interview Details Display -->
                                        @if($application->hasInterview())
                                            <div class="mt-1 text-xs text-gray-500">
                                                <div>📅 {{ $application->formatted_interview_date }}</div>
                                                <div>🕐 {{ $application->formatted_interview_time }}</div>
                                                <div>📍 {{ $application->interview_location }}</div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $application->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($application->status === 'pending')
                                            <!-- Schedule Interview Button -->
                                            <div class="flex flex-col space-y-2">
                                                <button onclick="openInterviewModal({{ $application->id }}, '{{ $application->user->name }}')"
                                                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-xs font-medium">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Jadwalkan Interview
                                                </button>
                                                
                                                <!-- Direct Reject -->
                                                <form method="POST" action="{{ route('admin.reject-volunteer', $application->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-xs font-medium w-full justify-center"
                                                            onclick="return confirm('Yakin ingin menolak aplikasi ini?')">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                            
                                        @elseif($application->status === 'interview_scheduled')
                                            <!-- Final Decision Buttons -->
                                            <div class="flex flex-col space-y-2">
                                                <form method="POST" action="{{ route('admin.accept-volunteer', $application->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition text-xs font-medium w-full justify-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Terima
                                                    </button>
                                                </form>
                                                
                                                <form method="POST" action="{{ route('admin.reject-volunteer', $application->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-xs font-medium w-full justify-center"
                                                            onclick="return confirm('Yakin ingin menolak aplikasi ini?')">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Tolak
                                                    </button>
                                                </form>
                                                
                                                <!-- Edit Interview Button -->
                                                <button onclick="editInterviewModal({{ $application->id }}, '{{ $application->user->name }}', '{{ $application->interview_date?->format('Y-m-d') }}', '{{ $application->interview_time?->format('H:i') }}', '{{ $application->interview_location }}', '{{ $application->interview_notes }}')"
                                                        class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition text-xs font-medium w-full justify-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                    </svg>
                                                    Edit Interview
                                                </button>
                                            </div>
                                            
                                        @else
                                            <span class="text-gray-400 text-xs italic">
                                                {{ $application->status === 'accepted' ? 'Sudah Diterima' : 'Sudah Ditolak' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-gray-800 mb-2">Belum ada yang mendaftar</h4>
                    <p class="text-gray-600">Belum ada yang mendaftar untuk posisi ini</p>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-6">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Belum Ada Posisi Volunteer</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">Mulai dengan membuat posisi volunteer untuk event ini. Volunteer akan membantu kesuksesan acara Anda.</p>
            <a href="{{ route('admin.volunteer-positions', $event->id) }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Buat Posisi Volunteer
            </a>
        </div>
    @endforelse

    <!-- Quick Actions -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <div class="flex items-center justify-center h-8 w-8 rounded-md bg-blue-500 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="ml-3 text-lg font-semibold text-blue-900">Quick Actions</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.volunteer-positions', $event->id) }}" 
               class="group block p-4 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-blue-900 group-hover:text-blue-800">Kelola Posisi</div>
                        <div class="text-sm text-blue-700">Tambah atau edit posisi volunteer</div>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.participants', $event->id) }}" 
               class="group block p-4 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-blue-900 group-hover:text-blue-800">Kelola Peserta</div>
                        <div class="text-sm text-blue-700">Lihat dan kelola pendaftar peserta</div>
                    </div>
                </div>
            </a>
            <a href="{{ route('events.show', $event->id) }}" 
               class="group block p-4 bg-white border border-blue-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="font-medium text-blue-900 group-hover:text-blue-800">Lihat Event</div>
                        <div class="text-sm text-blue-700">Lihat halaman publik event</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Interview Schedule Modal -->
<div id="interviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Jadwalkan Interview</h3>
                <button onclick="closeInterviewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="interviewForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kandidat</label>
                        <input type="text" id="candidateName" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                    </div>
                    
                    <div>
                        <label for="interview_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Interview *</label>
                        <input type="date" name="interview_date" id="interview_date" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="interview_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Interview *</label>
                        <input type="time" name="interview_time" id="interview_time" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="interview_location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Interview *</label>
                        <input type="text" name="interview_location" id="interview_location" required placeholder="Contoh: Ruang Meeting Lt. 2"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="interview_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Interview</label>
                        <textarea name="interview_notes" id="interview_notes" rows="3" placeholder="Tambahkan catatan atau instruksi khusus..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeInterviewModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Jadwalkan Interview
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentApplicationId = null;
    let isEditMode = false;

    function openInterviewModal(applicationId, candidateName) {
        currentApplicationId = applicationId;
        isEditMode = false;
        
        document.getElementById('modalTitle').textContent = 'Jadwalkan Interview';
        document.getElementById('candidateName').value = candidateName;
        document.getElementById('submitBtn').textContent = 'Jadwalkan Interview';
        
        // Clear form
        document.getElementById('interview_date').value = '';
        document.getElementById('interview_time').value = '';
        document.getElementById('interview_location').value = '';
        document.getElementById('interview_notes').value = '';
        
        // Set form action
        document.getElementById('interviewForm').action = `/admin/volunteer/${applicationId}/schedule-interview`;
        
        document.getElementById('interviewModal').classList.remove('hidden');
    }

    function editInterviewModal(applicationId, candidateName, date, time, location, notes) {
        currentApplicationId = applicationId;
        isEditMode = true;
        
        document.getElementById('modalTitle').textContent = 'Edit Jadwal Interview';
        document.getElementById('candidateName').value = candidateName;
        document.getElementById('submitBtn').textContent = 'Update Interview';
        
        // Fill existing data
        document.getElementById('interview_date').value = date || '';
        document.getElementById('interview_time').value = time || '';
        document.getElementById('interview_location').value = location || '';
        document.getElementById('interview_notes').value = notes || '';
        
        // Set form action
        document.getElementById('interviewForm').action = `/admin/volunteer/${applicationId}/update-interview`;
        
        document.getElementById('interviewModal').classList.remove('hidden');
    }

    function closeInterviewModal() {
        document.getElementById('interviewModal').classList.add('hidden');
        currentApplicationId = null;
        isEditMode = false;
    }

    // Set minimum date to today
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('interview_date').setAttribute('min', today);
    });

    // Close modal when clicking outside
    document.getElementById('interviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeInterviewModal();
        }
    });
</script>
@endsection