{{-- resources/views/volunteer/register.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-4xl">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('events.show', $event->id) }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </a>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Form Pendaftaran Panitia</h1>
            <p class="text-gray-600">{{ $event->name }}</p>
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} •
                {{ $event->location }}
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Volunteer Positions -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Pilih Posisi Volunteer</h2>

            @if($volunteerPositions->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($volunteerPositions as $position)
                        @php
                            $remaining = $position->remaining_quota;
                            $acceptedCount = $position->volunteerApplications->where('status', 'accepted')->count();
                            $pendingCount = $position->volunteerApplications->where('status', 'pending')->count();
                            $interviewCount = $position->volunteerApplications->where('status', 'interview_scheduled')->count();
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition cursor-pointer position-card {{ $remaining <= 0 ? 'bg-gray-50 opacity-60' : '' }}"
                            data-position-id="{{ $position->id }}" {{ $remaining <= 0 ? 'data-disabled=true' : '' }}>
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $position->position_name }}</h3>
                                @if($remaining > 0)
                                    <input type="radio" name="selected_position" value="{{ $position->id }}" class="mt-1">
                                @else
                                    <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full font-medium">PENUH</span>
                                @endif
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Job Description:</p>
                                    <p class="text-sm text-gray-700">{{ $position->description }}</p>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <div>
                                        <span class="font-medium text-gray-600">Kuota: </span>
                                        <span class="text-gray-800">{{ $position->initial_quota }} orang</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">Deadline: </span>
                                        <span
                                            class="text-gray-800">{{ \Carbon\Carbon::parse($position->deadline)->format('d M Y') }}</span>
                                    </div>
                                </div>

                                <!-- Applications count with detailed status breakdown -->
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="space-y-2">
                                        <!-- Total applications and remaining slots -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">
                                                {{ $position->volunteerApplications->count() }} total pendaftar
                                            </span>
                                            @php
                                                $accepted = $position->volunteerApplications->where('status', 'accepted')->count();
                                                $remaining = $position->initial_quota - $accepted;
                                            @endphp
                                            @if($remaining > 0)
                                                <span class="text-xs text-green-600 font-medium">
                                                    {{ $remaining }} slot tersisa
                                                </span>
                                            @else
                                                <span class="text-xs text-red-600 font-medium">
                                                    Kuota penuh
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Form -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <form id="volunteerForm" method="POST" action="{{ route('volunteer.register-post', $event->id) }}">
                        @csrf
                        <input type="hidden" name="position_id" id="position_id" required>

                        <div class="flex items-center justify-end space-x-4">
                            <button type="button" id="confirmBtn" disabled
                                class="px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800 disabled:bg-gray-300 disabled:cursor-not-allowed transition font-medium">
                                Daftar
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Belum Ada Posisi Volunteer</h3>
                    <p class="text-gray-600">Posisi volunteer untuk event ini belum tersedia. Silakan kembali lagi nanti.</p>
                    <a href="{{ route('events.show', $event->id) }}"
                        class="inline-block mt-4 px-6 py-3 bg-blue-950 text-white rounded-lg hover:bg-blue-800 transition">
                        Kembali ke Detail Event
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin mendaftar untuk posisi ini?</p>
            <div class="flex items-center justify-end space-x-4">
                <button type="button" id="cancelBtn"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="button" id="confirmSubmit"
                    class="px-4 py-2 bg-blue-950 text-white rounded hover:bg-blue-800 transition">
                    Ya
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const positionCards = document.querySelectorAll('.position-card');
            const confirmBtn = document.getElementById('confirmBtn');
            const positionIdInput = document.getElementById('position_id');
            const confirmationModal = document.getElementById('confirmationModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmSubmit = document.getElementById('confirmSubmit');
            const volunteerForm = document.getElementById('volunteerForm');

            // Handle position selection
            positionCards.forEach(card => {
                card.addEventListener('click', function () {
                    // Check if position is disabled (full quota)
                    if (this.dataset.disabled === 'true') {
                        return; // Don't allow selection of full positions
                    }

                    // Remove selection from all cards
                    positionCards.forEach(c => {
                        c.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                        const radio = c.querySelector('input[type="radio"]');
                        if (radio) radio.checked = false;
                    });

                    // Add selection to clicked card
                    this.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;

                    // Enable submit button and set position ID
                    const positionId = this.dataset.positionId;
                    positionIdInput.value = positionId;
                    confirmBtn.disabled = false;
                });
            });

            // Handle confirm button click
            confirmBtn.addEventListener('click', function () {
                if (positionIdInput.value) {
                    confirmationModal.classList.remove('hidden');
                }
            });

            // Handle modal cancel
            cancelBtn.addEventListener('click', function () {
                confirmationModal.classList.add('hidden');
            });

            // Handle modal confirm
            confirmSubmit.addEventListener('click', function () {
                volunteerForm.submit();
            });

            // Close modal when clicking outside
            confirmationModal.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    </script>
@endsection