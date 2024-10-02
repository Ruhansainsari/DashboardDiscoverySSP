@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">Reviews for Ad: {{ $ad->topic }}</h1>

    @if($ratings->isEmpty())
        <p class="text-center text-gray-500">No reviews found for this ad.</p>
    @else
        <div class="bg-white shadow-lg rounded-lg p-6">
            @foreach($ratings as $rating)
                <div class="review bg-gray-50 rounded-lg p-5 mb-6 shadow-md transition duration-300 hover:shadow-lg border-l-4 border-blue-500">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-2xl font-semibold text-gray-800">{{ $rating->user->first_name }} {{ $rating->user->last_name }}</h2>
                        <span class="bg-blue-500 text-white text-sm px-3 py-1 rounded-full">{{ $rating->rating }} / 5</span>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $rating->review }}</p>
                    <p class="text-sm text-gray-500">Posted on: {{ $rating->created_at->format('M d, Y') }}</p>

                    <!-- Delete button -->
                    <form action="{{ route('admin.ratings.delete', $rating->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200">
                            <span class="material-icons mr-1">delete</span> <!-- Google icon for delete -->
                            Delete
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex justify-center mt-8">
        <a href="{{ route('admin.ads.approved') }}" class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition duration-200 shadow-md">
            Back to Ads
        </a>
    </div>
</div>

@section('scripts')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endsection
@endsection
