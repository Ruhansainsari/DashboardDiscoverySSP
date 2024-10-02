<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'city' => 'required|string|max:255', // Validate city
        ]);
    
        // Handle image upload
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public'); // Store in 'ads' folder in 'public' disk
        }
    
        $ad = Ad::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'topic' => $request->topic,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $path, // Save the relative path to the image
            'status' => 'pending',
            'city' => $request->city, // Save the city
        ]);
    
        return response()->json(['message' => 'Ad submitted for approval'], 201);
    }
    

    public function index()
    {
        // Load ads with user and category relationships
        $ads = Ad::with(['user', 'category'])->where('status', 'pending')->get();

        return view('admin.ads.index', compact('ads'));
    }

    public function getUserPendingAds(Request $request)
    {
        $user = Auth::user();
        $ads = Ad::with('category')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->get();
    
        // Add the full URL to the image path
        $ads->map(function ($ad) {
            if ($ad->image) {
                $ad->image = asset('storage/' . $ad->image);
            }
            return $ad;
        });
    
        return response()->json($ads, 200);
    }

    public function getUserApprovedAds()
    {
        $user = Auth::user();
        $ads = Ad::with('category')
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->get();

        return response()->json($this->formatAdsWithImageUrls($ads), 200);
    }

    // Fetch rejected ads
    public function getUserRejectedAds()
    {
        $user = Auth::user();
        $ads = Ad::with('category')
                ->where('user_id', $user->id)
                ->where('status', 'rejected')
                ->get();

        return response()->json($this->formatAdsWithImageUrls($ads), 200);
    }

    // Helper function to add the full URL to the image path
    private function formatAdsWithImageUrls($ads)
    {
        return $ads->map(function ($ad) {
            if ($ad->image) {
                $ad->image = asset('storage/' . $ad->image);
            }
            return $ad;
        });
    }
    

}
