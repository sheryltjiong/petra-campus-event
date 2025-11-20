@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center px-4 py-8"
     style="background-image: url('{{ asset('images/background.jpg') }}')">

    <div class="bg-white bg-opacity-5 backdrop-blur-md rounded-2xl shadow-2xl p-8 w-full max-w-4xl flex flex-col md:flex-row items-center gap-10 relative text-white">

        <!-- Left Side - Welcome -->
        <div class="w-full md:w-1/2 text-left">
            <img src="{{ asset('images/logo.png') }}" alt="PCU Logo" class="h-10 w-10 mb-4"
                 onerror="this.style.display='none'">
            <h2 class="text-5xl font-extrabold mb-2">Welcome!</h2>
            <p class="text-lg text-gray-300">Don’t wait for opportunity, create it!</p>
        </div>

        <!-- Right Side - Sign Up Form -->
        <div class="w-full md:w-1/2 bg-white bg-opacity-10 rounded-xl p-6 shadow-inner">
            <h3 class="text-2xl font-bold mb-4 text-white text-center">Sign Up</h3>
            <p class="text-sm text-center text-gray-300 mb-6">Enter your details to create your account</p>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 text-sm p-3 rounded mb-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <input type="text" name="name" id="name" required
                       placeholder="Nama Lengkap"
                       value="{{ old('name') }}"
                       class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                <input type="email" name="email" id="email" required
                       placeholder="user@john.petra.ac.id"
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="line_id" id="line_id" required
                           placeholder="ID Line"
                           value="{{ old('line_id') }}"
                           class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                    <input type="tel" name="whatsapp" id="whatsapp" required
                           placeholder="Nomor WhatsApp"
                           value="{{ old('whatsapp') }}"
                           class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="nrp" id="nrp" required
                           placeholder="NRP"
                           value="{{ old('nrp') }}"
                           class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                    <select name="jurusan" id="jurusan" required
                            class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="Informatika" {{ old('jurusan') == 'Informatika' ? 'selected' : '' }}>Informatika</option>
                        <option value="Sistem Informasi Bisnis" {{ old('jurusan') == 'Sistem Informasi Bisnis' ? 'selected' : '' }}>Sistem Informasi Bisnis</option>
                        <option value="Data Science & Analytics" {{ old('jurusan') == 'Data Science & Analytics' ? 'selected' : '' }}>Data Science & Analytics</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="password" name="password" id="password" required
                           placeholder="Password"
                           class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />

                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           placeholder="Confirm Password"
                           class="w-full px-4 py-3 rounded bg-white bg-opacity-90 text-gray-800 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <div class="flex items-start space-x-2 text-sm text-gray-300">
                    <input type="checkbox" id="terms" name="terms" required class="accent-blue-500 mt-1" />
                    <label for="terms">
                        I agree to the <a href="#" class="text-blue-300 underline">Terms & Conditions</a>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-white text-gray-900 font-semibold px-4 py-3 rounded hover:bg-gray-200 transition">
                    Create Account
                </button>
            </form>

            <p class="text-sm text-gray-300 mt-6 text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-300 underline">Login</a>
            </p>
        </div>
    </div>
</div>
@endsection
