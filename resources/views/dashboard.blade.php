<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include TailwindCSS via CDN for styling (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-4xl bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-center mb-6">Admin Dashboard</h2>
            <div class="mb-6">
                <p>Welcome to the admin dashboard. You are logged in as <strong>{{ Auth::guard('admin')->user()->name }}</strong>.</p>
            </div>

            <div class="flex justify-center">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
