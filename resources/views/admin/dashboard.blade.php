@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-7xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                <p class="text-gray-600">{{ Auth::user()->name }}</p>
            </div>
            <a href="{{ route('admin.create-event-form') }}"
                class="bg-blue-950 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition font-medium">
                + Buat Event
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Event Aktif -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Event Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ $events->where('registration_phase', '!=', 'closed')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Jumlah Acara -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Jumlah Acara</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalEvents }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Applications -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Applications</p>
                        <p class="text-3xl font-bold text-yellow-600">
                            @php
                                $pendingVolunteers = 0;
                                $pendingParticipants = 0;
                                foreach($events as $event) {
                                    $pendingParticipants += $event->eventRegistrations->where('status', 'pending')->count();
                                    foreach($event->volunteerPositions as $position) {
                                        $pendingVolunteers += $position->volunteerApplications->where('status', 'pending')->count();
                                    }
                                }
                                $totalPending = $pendingVolunteers + $pendingParticipants;
                            @endphp
                            {{ $totalPending }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            V: {{ $pendingVolunteers }} • P: {{ $pendingParticipants }}
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Events This Week -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Events This Week</p>
                        <p class="text-3xl font-bold text-purple-600">
                            @php
                                $thisWeek = 0;
                                $today = \Carbon\Carbon::today();
                                $nextWeek = $today->copy()->addDays(7);
                                foreach($events as $event) {
                                    $eventDate = \Carbon\Carbon::parse($event->date);
                                    if($eventDate->between($today, $nextWeek)) {
                                        $thisWeek++;
                                    }
                                }
                            @endphp
                            {{ $thisWeek }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Next 7 days</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Action Cards -->
        @if($totalPending > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800 mb-1">Action Required</h3>
                    <p class="text-sm text-yellow-700">
                        You have {{ $totalPending }} applications waiting for review
                        @if($pendingVolunteers > 0)
                            ({{ $pendingVolunteers }} volunteers{{ $pendingParticipants > 0 ? ', ' . $pendingParticipants . ' participants' : '' }})
                        @elseif($pendingParticipants > 0)
                            ({{ $pendingParticipants }} participants)
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    @if($pendingVolunteers > 0)
                        <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ $pendingVolunteers }} Volunteers Pending
                        </span>
                    @endif
                    @if($pendingParticipants > 0)
                        <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ $pendingParticipants }} Participants Pending
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Event Management Table -->
        <div class="bg-white rounded-lg shadow-md">
            <!-- Enhanced Header with Filters -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <h2 class="text-xl font-semibold text-gray-800">Event Management</h2>
                    
                    <!-- Filters Row -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Input -->
                        <div class="relative">
                            <input type="text" id="searchEvents" placeholder="Search events..." 
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Status Filter -->
                        <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="volunteer_open">Volunteer Open</option>
                            <option value="participant_open">Participant Open</option>
                            <option value="closed">Closed</option>
                            <option value="completed">Completed</option>
                        </select>

                        <!-- Pending Filter -->
                        <select id="pendingFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Events</option>
                            <option value="has_pending">Has Pending</option>
                            <option value="no_pending">No Pending</option>
                        </select>

                        <!-- Date Range Filter -->
                        <select id="dateFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Dates</option>
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="past">Past Events</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters Display -->
                <div id="activeFilters" class="mt-4 flex flex-wrap gap-2 hidden">
                    <span class="text-sm text-gray-600">Active filters:</span>
                    <!-- Dynamic filter tags will be added here by JavaScript -->
                </div>
            </div>
            
            @if($events->isNotEmpty())
            <!-- Events Counter -->
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span id="eventsCount">Showing {{ $events->count() }} of {{ $events->count() }} events</span>
                    <div class="flex items-center space-x-4">
                        <label for="perPage" class="text-sm">Show:</label>
                        <select id="perPage" class="text-sm border border-gray-300 rounded px-2 py-1">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-sm">per page</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="eventsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">
                                Event <span class="ml-1">↕</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                                Tanggal <span class="ml-1">↕</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(2)">
                                Status <span class="ml-1">↕</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pending Reviews
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quick Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="eventsTableBody">
                        @foreach($events as $event)
                            <tr class="hover:bg-gray-50 event-row" 
                                data-name="{{ strtolower($event->name) }}"
                                data-location="{{ strtolower($event->location) }}"
                                data-status="{{ $event->registration_phase }}"
                                data-date="{{ $event->date }}"
                                data-pending="{{ 
                                    $event->eventRegistrations->where('status', 'pending')->count() + 
                                    $event->volunteerPositions->sum(function($pos) { return $pos->volunteerApplications->where('status', 'pending')->count(); })
                                }}">
                                <!-- Event Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $event->location }}</div>
                                        <div class="mt-1">
                                            <a href="{{ route('admin.events.edit', $event->id) }}" 
                                               class="inline-flex items-center px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date Column -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($event->date)->diffForHumans() }}
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event->registration_phase === 'volunteer_open')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Volunteer Open
                                        </span>
                                    @elseif($event->registration_phase === 'participant_open')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Participant Open
                                        </span>
                                    @elseif($event->registration_phase === 'closed')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Closed
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Completed
                                        </span>
                                    @endif
                                </td>

                                <!-- Pending Reviews Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $eventPendingParticipants = $event->eventRegistrations->where('status', 'pending')->count();
                                        $eventPendingVolunteers = 0;
                                        $eventInterviewVolunteers = 0;
                                        foreach($event->volunteerPositions as $position) {
                                            $eventPendingVolunteers += $position->volunteerApplications->where('status', 'pending')->count();
                                            $eventInterviewVolunteers += $position->volunteerApplications->where('status', 'interview_scheduled')->count();
                                        }
                                        $totalEventPending = $eventPendingParticipants + $eventPendingVolunteers;
                                    @endphp
                                    
                                    @if($totalEventPending > 0 || $eventInterviewVolunteers > 0)
                                        <div class="flex flex-col space-y-1">
                                            @if($eventPendingVolunteers > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    V: {{ $eventPendingVolunteers }} pending
                                                </span>
                                            @endif
                                            @if($eventInterviewVolunteers > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    V: {{ $eventInterviewVolunteers }} interview
                                                </span>
                                            @endif
                                            @if($eventPendingParticipants > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    P: {{ $eventPendingParticipants }} pending
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No pending</span>
                                    @endif
                                </td>

                                <!-- Quick Actions Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(Auth::user()->isSuperAdmin() || $event->users->contains(Auth::id()))
                                        <div class="flex flex-col space-y-2">
                                            <!-- Management Links -->
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.manage-volunteers', $event->id) }}"
                                                    class="inline-flex items-center px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                                    Volunteers
                                                    @if($eventPendingVolunteers > 0)
                                                        <span class="ml-1 bg-yellow-500 text-white rounded-full px-1 text-xs">{{ $eventPendingVolunteers }}</span>
                                                    @endif
                                                </a>

                                                <a href="{{ route('admin.participants', $event->id) }}"
                                                    class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200 transition">
                                                    Participants
                                                    @if($eventPendingParticipants > 0)
                                                        <span class="ml-1 bg-yellow-500 text-white rounded-full px-1 text-xs">{{ $eventPendingParticipants }}</span>
                                                    @endif
                                                </a>
                                            </div>
                                            
                                            <!-- Status Update -->
                                            <form method="POST" action="{{ route('admin.update-event-status', $event->id) }}" class="inline-block">
                                                @csrf
                                                <select name="status" onchange="this.form.submit()"
                                                    class="text-xs border border-gray-300 rounded px-2 py-1 w-full">
                                                    <option value="volunteer_open" {{ $event->registration_phase === 'volunteer_open' ? 'selected' : '' }}>
                                                        Volunteer Open
                                                    </option>
                                                    <option value="participant_open" {{ $event->registration_phase === 'participant_open' ? 'selected' : '' }}>
                                                        Participant Open
                                                    </option>
                                                    <option value="closed" {{ $event->registration_phase === 'closed' ? 'selected' : '' }}>
                                                        Closed
                                                    </option>
                                                    <option value="completed" {{ $event->registration_phase === 'completed' ? 'selected' : '' }}>
                                                        Completed
                                                    </option>
                                                </select>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-red-500 italic text-xs">No Access</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium" id="showingStart">1</span> to <span class="font-medium" id="showingEnd">{{ min(25, $events->count()) }}</span> of <span class="font-medium" id="totalItems">{{ $events->count() }}</span> results
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="prevPage" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            Previous
                        </button>
                        <div id="pageNumbers" class="flex space-x-1">
                            <!-- Page numbers will be generated by JavaScript -->
                        </div>
                        <button id="nextPage" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Belum ada event yang dibuat</h3>
                <p class="text-gray-600 mb-6">Mulai dengan membuat event pertama Anda.</p>
                <a href="{{ route('admin.create-event-form') }}"
                    class="inline-block bg-blue-950 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition">
                    Buat Event Pertama
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Enhanced Functionality -->
    <script>
        // Global variables for pagination and filtering
        let currentPage = 1;
        let itemsPerPage = 25;
        let allRows = [];
        let filteredRows = [];

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            allRows = Array.from(document.querySelectorAll('.event-row'));
            filteredRows = [...allRows];
            updateDisplay();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Search functionality
            document.getElementById('searchEvents').addEventListener('input', applyFilters);
            
            // Filter functionality
            document.getElementById('statusFilter').addEventListener('change', applyFilters);
            document.getElementById('pendingFilter').addEventListener('change', applyFilters);
            document.getElementById('dateFilter').addEventListener('change', applyFilters);
            
            // Items per page
            document.getElementById('perPage').addEventListener('change', function() {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                updateDisplay();
            });

            // Pagination buttons
            document.getElementById('prevPage').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateDisplay();
                }
            });

            document.getElementById('nextPage').addEventListener('click', function() {
                const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    updateDisplay();
                }
            });
        }

        // Apply all filters
        function applyFilters() {
            const searchTerm = document.getElementById('searchEvents').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const pendingFilter = document.getElementById('pendingFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            filteredRows = allRows.filter(row => {
                // Search filter
                const name = row.dataset.name;
                const location = row.dataset.location;
                const searchMatch = name.includes(searchTerm) || location.includes(searchTerm);

                // Status filter
                const status = row.dataset.status;
                const statusMatch = !statusFilter || status === statusFilter;

                // Pending filter
                const pending = parseInt(row.dataset.pending);
                let pendingMatch = true;
                if (pendingFilter === 'has_pending') {
                    pendingMatch = pending > 0;
                } else if (pendingFilter === 'no_pending') {
                    pendingMatch = pending === 0;
                }

                // Date filter
                const eventDate = new Date(row.dataset.date);
                const today = new Date();
                const weekFromNow = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);
                const monthFromNow = new Date(today.getTime() + 30 * 24 * 60 * 60 * 1000);
                
                let dateMatch = true;
                if (dateFilter === 'this_week') {
                    dateMatch = eventDate >= today && eventDate <= weekFromNow;
                } else if (dateFilter === 'this_month') {
                    dateMatch = eventDate >= today && eventDate <= monthFromNow;
                } else if (dateFilter === 'upcoming') {
                    dateMatch = eventDate >= today;
                } else if (dateFilter === 'past') {
                    dateMatch = eventDate < today;
                }

                return searchMatch && statusMatch && pendingMatch && dateMatch;
            });

            currentPage = 1;
            updateDisplay();
            updateActiveFilters();
        }

        // Update display based on current filters and pagination
        function updateDisplay() {
            // Hide all rows
            allRows.forEach(row => row.style.display = 'none');

            // Calculate pagination
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageRows = filteredRows.slice(startIndex, endIndex);

            // Show current page rows
            pageRows.forEach(row => row.style.display = '');

            // Update counters
            document.getElementById('eventsCount').textContent = `Showing ${filteredRows.length} of ${allRows.length} events`;
            document.getElementById('showingStart').textContent = filteredRows.length > 0 ? startIndex + 1 : 0;
            document.getElementById('showingEnd').textContent = Math.min(endIndex, filteredRows.length);
            document.getElementById('totalItems').textContent = filteredRows.length;

            // Update pagination controls
            updatePaginationControls();
        }

        // Update pagination controls
        function updatePaginationControls() {
            const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
            
            // Update prev/next buttons
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;

            // Generate page numbers
            const pageNumbers = document.getElementById('pageNumbers');
            pageNumbers.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    const button = document.createElement('button');
                    button.textContent = i;
                    button.className = `px-3 py-2 text-sm font-medium border rounded-md ${
                        i === currentPage 
                            ? 'bg-blue-500 text-white border-blue-500' 
                            : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'
                    }`;
                    button.onclick = () => {
                        currentPage = i;
                        updateDisplay();
                    };
                    pageNumbers.appendChild(button);
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-3 py-2 text-sm text-gray-500';
                    pageNumbers.appendChild(ellipsis);
                }
            }
        }

        // Update active filters display
        function updateActiveFilters() {
            const activeFiltersDiv = document.getElementById('activeFilters');
            const filters = [];

            const searchTerm = document.getElementById('searchEvents').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const pendingFilter = document.getElementById('pendingFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            if (searchTerm) filters.push(`Search: "${searchTerm}"`);
            if (statusFilter) filters.push(`Status: ${statusFilter.replace('_', ' ')}`);
            if (pendingFilter) filters.push(`Pending: ${pendingFilter.replace('_', ' ')}`);
            if (dateFilter) filters.push(`Date: ${dateFilter.replace('_', ' ')}`);

            if (filters.length > 0) {
                activeFiltersDiv.innerHTML = `
                    <span class="text-sm text-gray-600">Active filters:</span>
                    ${filters.map(filter => `<span class="inline-flex items-center px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">${filter}</span>`).join('')}
                    <button onclick="clearAllFilters()" class="text-xs text-red-600 hover:text-red-800 underline">Clear all</button>
                `;
                activeFiltersDiv.classList.remove('hidden');
            } else {
                activeFiltersDiv.classList.add('hidden');
            }
        }

        // Clear all filters
        function clearAllFilters() {
            document.getElementById('searchEvents').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('pendingFilter').value = '';
            document.getElementById('dateFilter').value = '';
            applyFilters();
        }

        // Sort table functionality
        let sortColumn = -1;
        let sortDirection = 1;

        function sortTable(columnIndex) {
            if (sortColumn === columnIndex) {
                sortDirection *= -1;
            } else {
                sortColumn = columnIndex;
                sortDirection = 1;
            }

            filteredRows.sort((a, b) => {
                let aValue, bValue;
                
                switch (columnIndex) {
                    case 0: // Event name
                        aValue = a.dataset.name;
                        bValue = b.dataset.name;
                        break;
                    case 1: // Date
                        aValue = new Date(a.dataset.date);
                        bValue = new Date(b.dataset.date);
                        break;
                    case 2: // Status
                        aValue = a.dataset.status;
                        bValue = b.dataset.status;
                        break;
                    default:
                        return 0;
                }

                if (aValue < bValue) return -1 * sortDirection;
                if (aValue > bValue) return 1 * sortDirection;
                return 0;
            });

            currentPage = 1;
            updateDisplay();
        }
    </script>
@endsection