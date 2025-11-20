{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCU Events - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#EBEAE9] min-h-screen">
    <!-- Navigation -->
    <nav class="bg-blue-950 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="PCU Logo" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
                    <span class="text-xl font-bold">PCU Events</span>
                    <span class="text-sm bg-blue-800 px-2 py-1 rounded">Admin</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="hover:text-gray-300 transition {{ Request::routeIs('admin.dashboard') ? 'text-yellow-300 font-medium' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.create-event-form') }}" 
                       class="hover:text-gray-300 transition {{ Request::routeIs('admin.create-event*') ? 'text-yellow-300 font-medium' : '' }}">
                        Buat Event
                    </a>
                    <a href="{{ route('admin.users') }}" 
                       class="hover:text-gray-300 transition {{ Request::routeIs('admin.users*') ? 'text-yellow-300 font-medium' : '' }}">
                        Kelola Pengguna
                    </a>
                    <a href="{{ route('home') }}" 
                       class="hover:text-gray-300 transition">
                        Beranda
                    </a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="hidden md:block text-right">
                        <p class="text-sm">Welcome Back, {{ explode(' ', Auth::user()->name)[0] }}!</p>
                        <p class="text-xs text-gray-300">{{ Auth::user()->name }}</p>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div class="relative">
                        <button id="userMenuBtn" class="flex items-center space-x-2 hover:text-gray-300 transition">
                            <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                <p class="font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                User Dashboard
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 pt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 pt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="max-w-7xl mx-auto px-4 pt-4">
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
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">© Petra Christian University Events 2025</p>
            <div class="mt-4 space-x-6">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white text-sm">Beranda</a>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white text-sm">Dashboard</a>
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm">Login</a>
            </div>
        </div>
    </footer>

    <script>
        // Toggle user menu
        document.getElementById('userMenuBtn').addEventListener('click', function() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userMenu');
            const button = document.getElementById('userMenuBtn');
            
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>