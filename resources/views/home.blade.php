<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rental Alat Berat</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-lg text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">🚜 Sistem Rental Alat Berat</h1>
        <p class="text-gray-600 mb-6">Selamat datang di sistem manajemen penyewaan alat berat.</p>

        @if (Route::has('login'))
            <div class="flex justify-center space-x-4">
                @auth
                    <a href="{{ url('/home') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</body>
</html>
