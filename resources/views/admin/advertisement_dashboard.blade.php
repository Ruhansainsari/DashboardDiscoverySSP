<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisement Dashboard</title>
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
                            <a href="{{ route('admin.advertisement_dashboard') }}" class="py-2 px-6 font-semibold text-purple-500 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition duration-300">
                                Advertisement Analytics
                            </a>
                            <a href="{{ route('admin.revenue') }}" class="py-2 px-6 font-semibold text-gray-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition duration-300">
                           Revenue
                        </a>
                        </div>
                    </div>
                </div>

                <!-- Charts Container -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Monthly Ad Postings -->
                    <div class="bg-white shadow-md rounded p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Monthly Ad Postings</h2>
                        <canvas id="monthlyAdChart"></canvas>
                    </div>

                    <!-- Ads by Category -->
                    <div class="bg-white shadow-md rounded p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Ads Count by Category</h2>
                        <canvas id="adsByCategoryChart"></canvas>
                    </div>

                    <!-- Ads by City -->
                    <div class="bg-white shadow-md rounded p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Ads Count by City</h2>
                        <canvas id="adsByCityChart"></canvas>
                    </div>

                    
                    <!-- Monthly Ads Chart -->
                    <div class="bg-white shadow-md rounded p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Monthly Approved and Rejected Ads</h2>
                        <canvas id="monthlyAdsChart"></canvas>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Ad Postings Chart
            var ctx = document.getElementById('monthlyAdChart').getContext('2d');
            var monthlyAdChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyAds->pluck('month')) !!}, // Months
                    datasets: [{
                        label: 'Monthly Ad Postings',
                        data: {!! json_encode($monthlyAds->pluck('count')) !!}, // Number of ads
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Ads by Category Chart
            var categories = @json($categories->pluck('name'));
            var adsCount = @json($categories->pluck('ads_count'));

            var ctx1 = document.getElementById('adsByCategoryChart').getContext('2d');
            var chart1 = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: categories,
                    datasets: [{
                        label: 'Number of Ads',
                        data: adsCount,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
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

            // Ads by City Chart
            var adsByCityData = @json($adsByCity);
            var cities = adsByCityData.map(function(ad) { return ad.city; });
            var adsCountByCity = adsByCityData.map(function(ad) { return ad.total; });

            var ctx2 = document.getElementById('adsByCityChart').getContext('2d');
            var adsByCityChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: cities,
                    datasets: [{
                        label: 'Number of Ads',
                        data: adsCountByCity,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
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
        });

        const months = @json($months);
        const approvedCounts = @json($approvedCounts);
        const rejectedCounts = @json($rejectedCounts);

        const ctx = document.getElementById('monthlyAdsChart').getContext('2d');
        const monthlyAdsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Approved Ads',
                        data: approvedCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    },
                    {
                        label: 'Rejected Ads',
                        data: rejectedCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Ads'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
