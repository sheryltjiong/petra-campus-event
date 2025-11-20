@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-3xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('events.show', $event->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Detail Event
        </a>
    </div>

    <!-- Form Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-blue-900 mb-2">Form Pendaftaran Peserta</h1>
        <p class="text-lg text-gray-700 font-medium">{{ $event->name }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-md rounded-lg p-6">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('events.register-participant', $event->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama_lengkap" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('nama_lengkap', $user->name) }}">
            </div>

            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('email', $user->email) }}">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-800 font-medium mb-1">NRP <span class="text-red-500">*</span></label>
                    <input type="text" name="nrp" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('nrp') }}">
                </div>
                <div>
                    <label class="block text-gray-800 font-medium mb-1">Jurusan <span class="text-red-500">*</span></label>
                    <input type="text" name="jurusan" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('jurusan') }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-1">Info Tambahan (Opsional)</label>
                <textarea name="additional_info" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('additional_info') }}</textarea>
            </div>

            <div class="mb-6 flex items-start space-x-2">
                <input type="checkbox" name="terms" id="terms" required class="mt-1">
                <label for="terms" class="text-sm text-gray-700">Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat & ketentuan</a> pendaftaran ini.</label>
            </div>

            <button type="submit" class="w-full bg-blue-950 hover:bg-blue-800 text-white font-medium py-3 rounded-lg transition">
                Daftar sebagai Peserta
            </button>
        </form>
    </div>
</div>
@endsection
