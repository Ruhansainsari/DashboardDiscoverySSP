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
                    <a href="{{ route('admin.dashboard') }}" class="py-2 px-6 font-semibold text-purple-500 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition duration-300">
                        User Analytics
                    </a>
                        <a href="{{ route('admin.advertisement_dashboard') }}" class="py-2 px-6 font-semibold text-gray-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition duration-300">
                            Advertisement Analytics
                        </a>
                        <a href="{{ route('admin.revenue') }}" class="py-2 px-6 font-semibold text-gray-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded transition duration-300">
                           Revenue
                        </a>
                    </div>
                </div>
            </div>

<!-- Charts Container -->
<!-- Charts Container -->
<div class="flex flex-wrap -mx-4 mb-6">

    <!-- Users Registered Monthly Chart -->
    <div class="w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Users Registered Monthly</h2>
            <canvas id="monthlyUsersChart"></canvas>
        </div>
    </div>

    <!-- Donut Chart for Ad Status -->
    <div class="w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Ad Status Overview</h2>
            <canvas id="adStatusChart"></canvas>
        </div>
    </div>

    <!-- Users by City -->
    <div class="w-full md:w-1/2 lg:w-1/3 p-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Users by City</h2>
            <canvas id="usersByCityChart"></canvas>
        </div>
    </div>
</div>


            </div>  
        </div>
    </div>

    <script>

            // Ad Status Donut Chart
    const approvedAdsCount = {{ $approvedAdsCount }};
    const rejectedAdsCount = {{ $rejectedAdsCount }};
    const pendingAdsCount = {{ $pendingAdsCount }};

    const adStatusChartCtx = document.getElementById('adStatusChart').getContext('2d');
    const adStatusChart = new Chart(adStatusChartCtx, {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Rejected', 'Pending'],
            datasets: [{
                data: [approvedAdsCount, rejectedAdsCount, pendingAdsCount],
                backgroundColor: ['#4caf50', '#f44336', '#ff9800'], // green, red, orange
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Advertisement Status Overview'
                }
            }
        }
    });


        // Monthly Users Bar Chart
        const monthlyUsersData = @json($monthlyUserCounts);
    const monthlyUserLabels = @json($monthlyUserLabels);

    const ctx = document.getElementById('monthlyUsersChart').getContext('2d');
    const monthlyUsersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyUserLabels,
            datasets: [{
                label: 'Number of Users Registered',
                data: monthlyUsersData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Users'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });

        // Users by City Pie Chart
        const usersByCityData = @json($usersByCity);
        const cityLabels = usersByCityData.map(city => city.city);
        const cityTotals = usersByCityData.map(city => city.total);
        const totalUsers = cityTotals.reduce((acc, total) => acc + total, 0);
        const cityPercentages = cityTotals.map(total => ((total / totalUsers) * 100).toFixed(2));

        const ctx3 = document.getElementById('usersByCityChart').getContext('2d');
        const usersByCityChart = new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: cityLabels.map((label, index) => `${label} (${cityPercentages[index]}%)`),
                datasets: [{
                    label: 'Number of Users',
                    data: cityTotals,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Users Distribution by City'
                    }
                }
            }
        });

    </script>

</body>
</html>
