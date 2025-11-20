{{-- resources/views/admin/participants.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-7xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Kelola Peserta</h1>
            <p class="text-gray-600">{{ $event->name }}</p>
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->date)->format('l, d M Y') }} • {{ $event->location }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.manage-volunteers', $event->id) }}" 
               class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                Kelola Volunteer
            </a>
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pendaftar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $participants->count() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Diterima</p>
                    <p class="text-2xl font-bold text-green-600">{{ $participants->where('status', 'approved')->count() }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Menunggu</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $participants->where('status', 'pending')->count() }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Slot Tersedia</p>
                    <p class="text-2xl font-bold text-blue-600">{{ max(0, $event->slot_peserta - $participants->where('status', 'approved')->count()) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Data Pendaftar</h2>
                <div class="flex space-x-2">
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Diterima</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                    <button onclick="window.print()" 
                            class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                        Export
                    </button>
                </div>
            </div>
        </div>

        @if($participants->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full" id="participantsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pendaftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyetujuan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($participants as $participant)
                            <tr class="hover:bg-gray-50" data-status="{{ $participant->status }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $participant->user->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $participant->user->nrp ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $participant->user->jurusan ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $participant->user->email ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($participant->status === 'approved')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Diterima
                                        </span>
                                    @elseif($participant->status === 'rejected')
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
                                    {{ $participant->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    @if($participant->status === 'pending')
                                        <form method="POST" action="{{ route('admin.approve-participant', $participant->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition text-xs">
                                                Terima
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.reject-participant', $participant->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-xs">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        @if($participant->status === 'approved')
                                            <span class="text-green-600 text-xs">Sudah Diterima</span>
                                        @elseif($participant->status === 'rejected')
                                            <span class="text-red-600 text-xs">Sudah Ditolak</span>
                                        @else
                                            <span class="text-yellow-600 text-xs">Menunggu Persetujuan</span>
                                        @endif

                                        @if($participant->approver)
                                            <br><span class="text-gray-500 text-xs">oleh {{ $participant->approver->name }}</span>
                                        @endif
                                    @endif
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Belum Ada Pendaftar</h3>
                <p class="text-gray-600">Belum ada yang mendaftar sebagai peserta untuk event ini.</p>
            </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    @if($participants->where('status', 'pending')->count() > 0)
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">Bulk Actions</h3>
                    <p class="text-xs text-yellow-700">{{ $participants->where('status', 'pending')->count() }} pendaftar menunggu persetujuan</p>
                </div>
                <div class="space-x-2">
                    <button onclick="approveAll()" 
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
                        Terima Semua
                    </button>
                    <button onclick="rejectAll()" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
                        Tolak Semua
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const filterValue = this.value;
    const rows = document.querySelectorAll('#participantsTable tbody tr');
    
    rows.forEach(row => {
        if (filterValue === '' || row.dataset.status === filterValue) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Bulk approve function
function approveAll() {
    if (confirm('Apakah Anda yakin ingin menerima semua pendaftar yang pending?')) {
        // Implementation would require additional backend endpoint
        console.log('Approve all pending participants');
    }
}

// Bulk reject function
function rejectAll() {
    if (confirm('Apakah Anda yakin ingin menolak semua pendaftar yang pending?')) {
        // Implementation would require additional backend endpoint
        console.log('Reject all pending participants');
    }
}
</script>
@endsection