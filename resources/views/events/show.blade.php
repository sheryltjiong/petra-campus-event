{{-- resources/views/events/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-4xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>
    </div>

    <!-- Event Detail Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Event Header -->
        <div class="bg-blue-950 text-white p-6">
            <h1 class="text-3xl font-bold mb-2">{{ $event->name }}</h1>
            <div class="flex flex-wrap gap-4 text-sm opacity-90">
                <span>📅 {{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }}</span>
                <span>🕐 {{ \Carbon\Carbon::parse($event->time ?? '00:00')->format('H:i') }} WIB</span>
                <span>📍 {{ $event->location ?? 'Lokasi TBA' }}</span>
            </div>
        </div>

        <!-- Event Details -->
        <div class="p-6">
            <!-- Event Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Detail Event</h3>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium">Penyelenggara:</span> {{ $event->organizer_type ?? 'PCU' }}</div>
                        @if($event->skkk_points || $event->skkkCategoryName)
                            <div>
                                <span class="font-medium">SKKK Points (Peserta):</span> 
                                {{ $event->skkk_points ?? 'Tidak tersedia' }}
                            </div>
                            @if($event->skkkCategoryName)
                                <div>
                                    <span class="font-medium">Kategori SKKK:</span> 
                                    {{ $event->skkkCategoryName }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                
                
                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Status Pendaftaran</h3>
                    <div class="space-y-2">
                        @if ($registration_phase === 'volunteer_open')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm">Pendaftaran Panitia: <span class="font-medium text-green-600">Terbuka</span></span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                <span class="text-sm">Pendaftaran Peserta: <span class="font-medium text-gray-600">Belum Dibuka</span></span>
                            </div>
                        @elseif ($registration_phase === 'participant_open')
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                <span class="text-sm">Pendaftaran Panitia: <span class="font-medium text-gray-600">Ditutup</span></span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm">Pendaftaran Peserta: <span class="font-medium text-green-600">Terbuka</span></span>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-sm">Pendaftaran: <span class="font-medium text-red-600">Ditutup</span></span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Event Description -->
            <div class="mb-8">
                <h3 class="font-semibold text-gray-800 mb-3">Deskripsi</h3>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            <!-- Registration Buttons -->
            <div class="border-t pt-6">
                @auth
                    @if ($registration_phase === 'volunteer_open')
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('volunteer.register', $event->id) }}" 
                               class="flex-1 bg-blue-950 text-white text-center px-6 py-3 rounded-lg hover:bg-blue-800 transition font-medium">
                                Join as panitia
                            </a>
                            <button disabled 
                                    class="flex-1 bg-gray-300 text-gray-500 text-center px-6 py-3 rounded-lg cursor-not-allowed font-medium">
                                Join as peserta (Belum Dibuka)
                            </button>
                        </div>
                    @elseif ($registration_phase === 'participant_open')
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button disabled 
                                    class="flex-1 bg-gray-300 text-gray-500 text-center px-6 py-3 rounded-lg cursor-not-allowed font-medium">
                                Join as panitia (Ditutup)
                            </button>
                            <a href="{{ route('events.participant-form', $event->id) }}" 
                               class="flex-1 bg-green-600 text-white text-center px-6 py-3 rounded-lg hover:bg-green-500 transition font-medium">
                                Join as peserta
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                                <p class="font-medium">Pendaftaran Ditutup</p>
                                <p class="text-sm">Maaf, pendaftaran untuk event ini sudah ditutup.</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">Silakan login untuk mendaftar ke event ini</p>
                        @if ($registration_phase === 'volunteer_open')
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('login') }}" 
                                   class="flex-1 bg-blue-950 text-white text-center px-6 py-3 rounded-lg hover:bg-blue-800 transition font-medium">
                                    Login untuk Daftar sebagai Panitia
                                </a>
                                <button disabled 
                                        class="flex-1 bg-gray-300 text-gray-500 text-center px-6 py-3 rounded-lg cursor-not-allowed font-medium">
                                    Daftar sebagai Peserta (Belum Dibuka)
                                </button>
                            </div>
                        @elseif ($registration_phase === 'participant_open')
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button disabled 
                                        class="flex-1 bg-gray-300 text-gray-500 text-center px-6 py-3 rounded-lg cursor-not-allowed font-medium">
                                    Daftar sebagai Panitia (Ditutup)
                                </button>
                                <a href="{{ route('login') }}" 
                                   class="flex-1 bg-green-600 text-white text-center px-6 py-3 rounded-lg hover:bg-green-500 transition font-medium">
                                    Login untuk Daftar sebagai Peserta
                                </a>
                            </div>
                        @else
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                                <p class="font-medium">Pendaftaran Ditutup</p>
                            </div>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- More Info Section (if needed) -->
    @if($event->additional_info ?? false)
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-blue-900 mb-2">More Info</h3>
            <p class="text-blue-800 text-sm">{{ $event->additional_info }}</p>
        </div>
    @endif
</div>
@endsection