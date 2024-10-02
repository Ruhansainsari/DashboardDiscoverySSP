<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
                        <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-700">Dashboard</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded hover:bg-gray-700">Categories</a>
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
            <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Admin Dashboard</h2>
            <p class="mb-4">Welcome to the admin dashboard. You are logged in as <strong>{{ Auth::guard('admin')->user()->name }}</strong>.</p>

                <!-- Count Section -->
                <div class="flex space-x-6 mb-6">
                    <!-- Total Users Card -->
                    <div class="bg-white shadow-lg rounded-lg p-8 flex items-center transition-transform transform hover:scale-105 hover:shadow-xl duration-300 border border-gray-200 w-1/3">
                        <span class="material-icons text-blue-500 text-6xl">people</span>
                        <div class="ml-6">
                            <h2 class="text-2xl font-semibold text-gray-700">Total Users</h2>
                            <p class="text-4xl font-bold text-gray-800">{{ $totalUsers }}</p>
                        </div>
                    </div>

                    <!-- Approved Ads Card -->
                    <div class="bg-white shadow-lg rounded-lg p-8 flex items-center transition-transform transform hover:scale-105 hover:shadow-xl duration-300 border border-gray-200 w-1/3">
                        <span class="material-icons text-green-500 text-6xl">check_circle</span>
                        <div class="ml-6">
                            <h2 class="text-2xl font-semibold text-gray-700">Approved Ads</h2>
                            <p class="text-4xl font-bold text-gray-800">{{ $approvedAdsCount }}</p>
                        </div>
                    </div>

                    <!-- Total Revenue Card -->
                    <div class="bg-white shadow-lg rounded-lg p-8 flex items-center transition-transform transform hover:scale-105 hover:shadow-xl duration-300 border border-gray-200 w-1/3">
                        <span class="material-icons text-yellow-500 text-6xl">attach_money</span>
                        <div class="ml-6">
                            <h2 class="text-2xl font-semibold text-gray-700">Total Revenue</h2>
                            <p class="text-4xl font-bold text-gray-800">${{ number_format($totalRevenue, 2) }}</p>
                        </div>
                    </div>
                </div>


                <!-- Tabs for User Analytics, Advertisement Analytics, Revenue -->
                <div class="mb-6">
                <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg">
                    <div class="flex justify-between border-b border-gray-300 mb-4">
                    <a href="{{ route('admin.dashboard') }}" class="py-2 px-6 font-semibold text-gray-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition duration-300">
                        User Analytics
                    </a>
                        <a href="{{ route('admin.advertisement_dashboard') }}" class="py-2 px-6 font-semibold text-gray-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition duration-300">
                            Advertisement Analytics
                        </a>
                        <a href="{{ route('admin.revenue') }}" class="py-2 px-6 font-semibold text-purple-500 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition duration-300">
                            Revenue
                        </a>
                    </div>
                </div>
            </div>

            <div style="width: 80%; margin: auto;">
                <canvas id="revenueChart"></canvas>
            </div>
            </div>  
        </div>
    </div>

    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthLabels),
                datasets: [{
                    label: 'Monthly Revenue (in $)',
                    data: @json($revenueData),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    </script>

</body>
</html>
