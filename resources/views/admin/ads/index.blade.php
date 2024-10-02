@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Pending Ads</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($ads as $ad)
                <div class="ad bg-white shadow-lg rounded-lg p-5 transition duration-300 hover:shadow-xl border-l-4 border-blue-500">
                    <div class="flex items-center mb-4">
                        @if ($ad->image)
                            <img src="{{ Storage::url($ad->image) }}" alt="Ad Image" class="w-full h-48 object-cover rounded-lg mb-4 shadow-sm">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center mb-4 shadow-sm">
                                <p class="text-gray-500">No Image</p>
                            </div>
                        @endif
                    </div>

                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $ad->topic }}</h2>
                    <p class="text-gray-700 mb-3">{{ $ad->description }}</p>
                    <div class="text-sm text-gray-500 mb-2">
                        <p>Price: <span class="font-semibold text-blue-600">${{ number_format($ad->price, 2) }}</span></p>
                        <p>City: <span class="font-semibold text-blue-600">{{ $ad->city }}</span></p>
                        <p>Category: <span class="font-semibold text-blue-600">{{ $ad->category->name }}</span></p>
                        <p>User: <span class="font-semibold text-blue-600">{{ $ad->user->first_name }} {{ $ad->user->last_name }} ({{ $ad->user->email }})</span></p>
                    </div>

                    <div class="flex justify-center mt-4 space-x-4">
                        <form action="{{ route('admin.ads.approve', $ad->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-200 shadow-md hover:shadow-lg">Approve</button>
                        </form>

                        <form action="{{ route('admin.ads.reject', $ad->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200 shadow-md hover:shadow-lg">Reject</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
