@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Search and Filter Ads</h1>

    <!-- Search and Filter Form -->
    <form action="{{ route('admin.ads.search') }}" method="GET" class="mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <input type="text" name="search" placeholder="Search by Ad Name, User Name, or City" class="border border-gray-300 rounded-lg p-2 mb-4 md:mb-0 md:mr-4 w-full md:w-1/3" value="{{ request('search') }}">
            
            <select name="category_id" class="border border-gray-300 rounded-lg p-2 mb-4 md:mb-0 md:mr-4 w-full md:w-1/4">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Search</button>
        </div>
    </form>

    <!-- Ads Listing -->
    @if($ads->isEmpty())
        <p class="text-center text-gray-500">No ads found.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ads as $ad)
                <div class="ad bg-white shadow-lg rounded-lg p-5 transition duration-300 hover:shadow-xl border-l-4 border-green-500">
                    <div class="mb-4">
                        @if($ad->image)
                            <img src="{{ asset('storage/' . $ad->image) }}" alt="Ad Image" class="w-full h-64 object-cover rounded-lg mb-4 shadow-sm">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center mb-4 shadow-sm">
                                <p class="text-gray-500">No Image</p>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $ad->topic }}</h2>
                        <p class="text-gray-700 mb-3">{{ $ad->description }}</p>
                        <div class="text-sm text-gray-500 mb-2">
                            <p>Price: <span class="font-semibold text-blue-600">${{ number_format($ad->price, 2) }}</span></p>
                            <p>City: <span class="font-semibold text-blue-600">{{ $ad->city }}</span></p>
                            <p>Category: <span class="font-semibold text-blue-600">{{ $ad->category->name }}</span></p>
                            <p>User: <span class="font-semibold text-blue-600">{{ $ad->user->first_name }} {{ $ad->user->last_name }} ({{ $ad->user->email }})</span></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
