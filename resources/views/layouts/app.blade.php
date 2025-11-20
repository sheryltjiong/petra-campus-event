{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCU Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#EBEAE9] min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-blue-950 text-white p-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="PCU Logo" class="mr-2 h-6 w-6 object-contain" onerror="this.style.display='none'">
            <span class="font-bold">PCU Events</span>
        </div>
        <div class="space-x-4">
            @auth
                @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300">Admin Dashboard</a>
                @endif
                 <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('dashboard') }}" class="hover:text-gray-300">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gray-300">Logout</button>
                </form>
            @else
                @if(Request::is('login'))
                    <a href="{{ route('register') }}" class="hover:text-gray-300">Sign Up</a>
                    <a href="{{ route('home') }}" class="hover:text-gray-300">Home</a>
                @elseif(Request::is('register'))
                    <a href="{{ route('login') }}" class="hover:text-gray-300">Login</a>
                    <a href="{{ route('home') }}" class="hover:text-gray-300">Home</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-gray-300">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-gray-300">Sign Up</a>
                    <a href="{{ route('home') }}" class="hover:text-gray-300">Home</a>
                @endif
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        @if(session('success'))
            <div class="max-w-6xl mx-auto px-4 pt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-6xl mx-auto px-4 pt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="max-w-6xl mx-auto px-4 pt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-sm">© Petra Christian University Events 2025</p>
            <div class="mt-4 space-x-6">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white text-sm">Home</a>
                @auth
                <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white text-sm">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm">Login</a>
                @endauth
            </div>
        </div>
    </footer>
</body>
</html>