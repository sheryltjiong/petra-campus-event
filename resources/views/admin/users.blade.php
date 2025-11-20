@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-8 px-4 max-w-7xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelola Pengguna</h1>
            <p class="text-gray-600">Manage admin assignments and user accounts</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex border-b border-gray-200 mb-8">
            <button onclick="showTab('adminTab')" id="adminTabBtn"
                class="px-6 py-3 font-semibold text-blue-900 border-b-2 border-blue-900 bg-blue-50 rounded-t-lg transition">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <span>Admin ({{ $admins->count() }})</span>
                </div>
            </button>
            <button onclick="showTab('mahasiswaTab')" id="mahasiswaTabBtn"
                class="px-6 py-3 font-semibold text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-t-lg transition">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                    <span>Mahasiswa ({{ $students->count() }})</span>
                </div>
            </button>
        </div>

        <!-- Admin Tab -->
        <div id="adminTab">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Admin</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage admin users and their event assignments</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Ditugaskan</th>
                                @if(Auth::user()->isSuperAdmin())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($admins as $admin)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-blue-600 font-medium text-sm">{{ substr($admin->name, 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $admin->email }}</div>
                                                @if($admin->nrp)
                                                    <div class="text-xs text-gray-400">NRP: {{ $admin->nrp }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($admin->line_id)
                                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                                    <div class="w-4 h-4 mr-2 bg-green-500 rounded-sm flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">L</span>
                                                    </div>
                                                    <span class="font-medium text-gray-700">{{ $admin->line_id }}</span>
                                                </div>
                                            @endif
                                            @if($admin->whatsapp)
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <div class="w-4 h-4 mr-2 bg-green-500 rounded-sm flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">W</span>
                                                    </div>
                                                    <span class="font-medium text-gray-700">{{ $admin->whatsapp}}</span>
                                                </div>
                                            @endif
                                            @if(!$admin->line_id && !$admin->whatsapp)
                                                <span class="text-sm text-gray-400 italic">No contact info</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($admin->managedEvents->count() > 0)
                                            <div class="space-y-1">
                                                @foreach($admin->managedEvents as $event)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $event->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 italic">Belum ada event</span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->isSuperAdmin())
                                        <td class="px-6 py-4">
                                            <div class="space-y-3">
                                                <!-- Assign Events Form -->
                                                <form method="POST" action="{{ route('admin.assign-event') }}" class="space-y-2">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $admin->id }}">
                                                    <select name="event_ids[]" multiple required
                                                        class="text-sm border border-gray-300 rounded-lg px-3 py-2 w-full max-w-48 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                        @foreach($events as $event)
                                                            <option value="{{ $event->id }}" {{ $admin->managedEvents->contains($event->id) ? 'selected' : '' }}>
                                                                {{ Str::limit($event->name, 30) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-xs text-gray-500">Hold Ctrl/Cmd for multiple</p>
                                                    <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                        Assign
                                                    </button>
                                                </form>

                                                <!-- Delete User Form -->
                                                <form method="POST" action="{{ route('admin.delete-user', $admin->id) }}" 
                                                    onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500 transition">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->isSuperAdmin() ? '4' : '3' }}" class="px-6 py-12 text-center">
                                        <div class="text-gray-400">
                                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-500">No admins found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Tab -->
        <div id="mahasiswaTab" class="hidden">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Mahasiswa</h2>
                    <p class="text-sm text-gray-600 mt-1">Registered student accounts</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                    <span class="text-green-600 font-medium text-sm">{{ substr($student->name, 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                                <div class="text-xs text-gray-400">Registered: {{ $student->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($student->nrp)
                                                <div class="text-sm font-medium text-gray-900">{{ $student->nrp }}</div>
                                            @endif
                                            @if($student->jurusan)
                                                <div class="text-sm text-gray-600">{{ $student->jurusan }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($student->line_id)
                                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                                    <div class="w-4 h-4 mr-2 bg-green-500 rounded-sm flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">L</span>
                                                    </div>
                                                    <span class="font-medium text-gray-700">{{ $student->line_id }}</span>
                                                </div>
                                            @endif
                                            @if($student->whatsapp)
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <div class="w-4 h-4 mr-2 bg-green-500 rounded-sm flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">W</span>
                                                    </div>
                                                    <span class="font-medium text-gray-700">{{ $student->whatsapp }}</span>
                                                </div>
                                            @endif
                                            @if(!$student->line_id && !$student->whatsapp)
                                                <span class="text-sm text-gray-400 italic">No contact info</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.delete-user', $student->id) }}" 
                                            onsubmit="return confirm('Are you sure you want to delete this student?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500 transition">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-400">
                                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-500">No students found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            // Hide all tabs
            document.getElementById('adminTab').classList.add('hidden');
            document.getElementById('mahasiswaTab').classList.add('hidden');
            
            // Reset tab button styles
            document.getElementById('adminTabBtn').className = 'px-6 py-3 font-semibold text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-t-lg transition';
            document.getElementById('mahasiswaTabBtn').className = 'px-6 py-3 font-semibold text-gray-600 hover:text-blue-900 hover:bg-gray-50 rounded-t-lg transition';
            
            // Show selected tab
            document.getElementById(tabId).classList.remove('hidden');
            
            // Update active tab button style
            if (tabId === 'adminTab') {
                document.getElementById('adminTabBtn').className = 'px-6 py-3 font-semibold text-blue-900 border-b-2 border-blue-900 bg-blue-50 rounded-t-lg transition';
            } else {
                document.getElementById('mahasiswaTabBtn').className = 'px-6 py-3 font-semibold text-blue-900 border-b-2 border-blue-900 bg-blue-50 rounded-t-lg transition';
            }
        }
    </script>
@endsection