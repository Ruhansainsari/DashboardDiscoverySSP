<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Include TailwindCSS via CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Inline gradient background */
        .bg-gradient-custom {
            background: linear-gradient(to bottom right, #7F27FF, #9F70FD, #FDBF60, #FF8911);
        }
    </style>
</head>
<body class="bg-gradient-custom flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Admin Login</h2>

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label for="email" class="block text-gray-700 text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required autofocus>
            </div>
            <div class="mb-5">
                <label for="password" class="block text-gray-700 text-sm font-medium">Password</label>
                <input type="password" name="password" id="password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div class="text-center">
                <button type="submit" class="w-full bg-indigo-600 text-white font-semibold px-4 py-3 rounded-lg transition duration-200 hover:bg-indigo-700 focus:outline-none">Login</button>
            </div>
        </form>

        <!-- Forgot Password Link -->
        <div class="mt-4 text-center">
            <a href="{{}}" class="text-sm text-indigo-600 hover:underline">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
