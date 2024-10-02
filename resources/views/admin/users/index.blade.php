<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="h-16 flex items-center justify-center bg-gray-900 text-xl font-bold">
                Admin Panel
            </div>
            <nav class="flex-1 px-4 py-6">
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Categories</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.ads.approved') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Advertisements</a>
                    </li>
                    <li class="mb-4">
                        <!-- Add Ad Approvals link here -->
                        <a href="{{ route('admin.ads.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">
                            Ad Approvals
                            <!-- Display notification badge if there are pending ads -->
                            @if(App\Models\Ad::where('status', 'pending')->count() > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">
                                    {{ App\Models\Ad::where('status', 'pending')->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded bg-gray-700">Users</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-700">Settings</a>
                    </li>
                    <li class="mb-4">
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left py-2.5 px-4 rounded hover:bg-red-600">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 p-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">All Users</h2>

                    <!-- Centered Search Bar -->
                    <div class="flex justify-center mb-6">
                        <form action="{{ route('admin.users.index') }}" method="GET" class="w-96 relative">
                            <!-- Search input -->
                            <input type="text" name="search" placeholder="Search" class="px-4 py-2 pr-10 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" />
                            <!-- Search icon button inside the input -->
                            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 focus:outline-none">
                                <span class="material-icons">search</span>
                            </button>
                        </form>
                    </div>

              <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 text-left leading-4 text-gray-600 tracking-wider">ID</th>
                                <th class="px-6 py-3 border-b-2 text-left leading-4 text-gray-600 tracking-wider">First Name</th>
                                <th class="px-6 py-3 border-b-2 text-left leading-4 text-gray-600 tracking-wider">Last Name</th>
                                <th class="px-6 py-3 border-b-2 text-left leading-4 text-gray-600 tracking-wider">City</th>
                                <th class="px-6 py-3 border-b-2 text-left leading-4 text-gray-600 tracking-wider">Email</th>
                                <th class="px-6 py-3 border-b-2 text-left leading-4 text-gray-600 tracking-wider">Date Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 border-b">{{ $user->id }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->first_name }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->last_name }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->city }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->email }}</td>
                                    <td class="px-6 py-4 border-b">{{ $user->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
