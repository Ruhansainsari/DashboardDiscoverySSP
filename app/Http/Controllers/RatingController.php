<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    // Add rating and review for an ad
    public function store(Request $request, $adId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $ad = Ad::findOrFail($adId);

        $rating = Rating::create([
            'user_id' => Auth::id(),
            'ad_id' => $adId,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json(['message' => 'Rating and review added successfully', 'rating' => $rating], 201);
    }

    // Fetch all ratings and reviews for a specific ad
    public function show($adId)
    {
        $ad = Ad::findOrFail($adId);
        $ratings = $ad->ratings()->with('user')->get();

        return response()->json($ratings, 200);
    }
}
