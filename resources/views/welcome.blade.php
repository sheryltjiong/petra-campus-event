{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCU Events - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8 text-center">
            <div class="mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="PCU Logo" class="h-16 w-16 mx-auto object-contain" onerror="this.style.display='none'">
                <h1 class="text-3xl font-bold text-gray-800 mt-4">PCU Events</h1>
                <p class="text-gray-600 mt-2">Platform Event Mahasiswa Petra Christian University</p>
            </div>
            
            <div class="space-y-4">
                <a href="{{ route('home') }}" 
                   class="block w-full bg-blue-950 text-white py-3 px-4 rounded-lg hover:bg-blue-800 transition font-medium">
                    Lihat Events
                </a>
                
                @guest
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('login') }}" 
                           class="block bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="block bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition font-medium">
                            Register
                        </a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="block w-full border border-blue-950 text-blue-950 py-3 px-4 rounded-lg hover:bg-blue-50 transition font-medium">
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</body>
</html>