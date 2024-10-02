<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar (left navigation) -->
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
                        <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded bg-gray-700">Categories</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.ads.approved') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Advertisements</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.ads.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">
                            Ad Approvals
                            @if(App\Models\Ad::where('status', 'pending')->count() > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">
                                    {{ App\Models\Ad::where('status', 'pending')->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Users</a>
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
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6 text-center">Add New Category</h2>

                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="bg-red-500 text-white p-4 mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" name="name" id="name" class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:border-blue-500" placeholder="Category Name" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
