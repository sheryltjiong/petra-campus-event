{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-6xl mb-16">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back, {{ explode(' ', $user->name)[0] }}!</h1>
            <p class="text-gray-600">{{ $user->name }}</p>
        </div>

        <!-- Interview Notifications -->
        @php
            $upcomingInterviews = $volunteerApplications->where('status', 'interview_scheduled')->filter(function ($app) {
                return $app->interview_date && $app->interview_date->isFuture();
            });
        @endphp

        @if($upcomingInterviews->count() > 0)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-blue-900">Interview Terjadwal!</h3>
                        <p class="text-sm text-blue-700">Anda memiliki {{ $upcomingInterviews->count() }} interview yang akan
                            datang</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($upcomingInterviews as $interview)
                        <div class="bg-white border border-blue-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $interview->event->name }}</h4>
                                    <p class="text-sm text-gray-600">Posisi: {{ $interview->position->position_name }}</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Interview
                                </span>
                            </div>

                            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $interview->formatted_interview_date }}
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $interview->formatted_interview_time }}
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $interview->interview_location }}
                                </div>
                            </div>

                            @if($interview->interview_notes)
                                <div class="mt-3 p-3 bg-gray-50 rounded-md">
                                    <p class="text-sm text-gray-700"><strong>Catatan:</strong> {{ $interview->interview_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- User Profile Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-800 uppercase">{{ $user->name }}</h2>
                    <p class="text-gray-600 text-lg">{{ $user->nrp }}</p>
                    <p class="text-gray-500">{{ $user->jurusan }}</p>
                </div>

                <div class="text-center bg-blue-50 rounded-lg p-4 mx-8">
                    <p class="text-sm text-gray-600 mb-1">Jumlah SKKK</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $user->getTotalSkkk() }}</p>
                </div>

                <div class="flex space-x-8">
                    @php
                        $totalEvents = $participantEvents
                            ->merge($volunteerEvents)
                            ->unique('id') // atau 'id' kalau pakai ID event
                            ->count();
                    @endphp

                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-800">{{ $totalEvents}}</p>
                        <p class="text-sm text-gray-600">Jumlah<br>Event</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-800">{{ $volunteerEvents->count() }}</p>
                        <p class="text-sm text-gray-600">Jumlah<br>Kepanitiaan</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rincian SKKK --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold mb-6 text-gray-800">Rincian Poin SKKK</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Penalaran</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $skkkSummary['A']}}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Bakat Minat</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $skkkSummary['B'] }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Organisasi & Kepemimpinan</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $skkkSummary['C']}}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500 mb-1">Pengabdian Masyarakat</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $skkkSummary['D'] }}</p>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Events as Participant -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-6 text-gray-800">Event sebagai peserta</h3>
                <div class="space-y-4">
                    @forelse ($participantEvents as $event)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <h4 class="font-semibold text-gray-800">{{ $event->name }}</h4>
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
                            @if(isset($event->location))
                                <p class="text-sm text-gray-500">{{ $event->location }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Belum ada event yang diikuti sebagai peserta</p>
                            <a href="{{ route('home') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Event Tersedia →
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Events as Volunteer -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-6 text-gray-800">Event sebagai volunteer</h3>
                <div class="space-y-4">
                    @forelse ($volunteerEvents as $event)
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <h4 class="font-semibold text-gray-800">{{ $event->name }}</h4>
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
                            @if(isset($event->position))
                                <p class="text-sm text-blue-600 font-medium">{{ $event->position }}</p>
                            @endif
                            @if(isset($event->location))
                                <p class="text-sm text-gray-500">{{ $event->location }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Belum ada event yang diikuti sebagai volunteer</p>
                            <a href="{{ route('home') }}" class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Event Tersedia →
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Registration Status Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-6 text-gray-800">Detail Pendaftaran</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Pendaftaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Detail</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Volunteer Applications --}}
                        @foreach ($volunteerApplications ?? [] as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $application->event->name ?? 'Event tidak ditemukan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $application->position->position_name ?? 'Volunteer' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($application->status === 'accepted')
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Diterima
                                        </span>
                                    @elseif($application->status === 'interview_scheduled')
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Interview Dijadwalkan
                                        </span>
                                    @elseif($application->status === 'rejected')
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($application->status === 'interview_scheduled' && $application->hasInterview())
                                        <div class="text-xs space-y-1">
                                            <div>📅 {{ $application->formatted_interview_date }}</div>
                                            <div>🕐 {{ $application->formatted_interview_time }}</div>
                                            <div>📍 {{ $application->interview_location }}</div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        {{-- Participant Registrations --}}
                        @foreach ($participantRegistrations ?? [] as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $registration->event->name ?? 'Event tidak ditemukan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    Peserta
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($registration->status === 'approved')
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Diterima
                                        </span>
                                    @elseif($registration->status === 'rejected')
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="text-gray-400">-</span>
                                </td>
                            </tr>
                        @endforeach

                        @if(($volunteerApplications->isEmpty() ?? true) && ($participantRegistrations->isEmpty() ?? true))
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <div class="text-gray-400 mb-2">
                                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </div>
                                    <p>Belum ada pendaftaran</p>
                                    <a href="{{ route('home') }}"
                                        class="inline-block mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                        Mulai Daftar Event →
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection