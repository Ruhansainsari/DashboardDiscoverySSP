<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Ad;
use App\Models\Category;
use App\Models\User;
use App\Models\Rating;

class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        } else {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    public function dashboard()
    {
        $categories = Category::withCount('ads')->get();
        $adsByCity = Ad::select('city', \DB::raw('count(*) as total'))
                        ->groupBy('city')
                        ->get();

        $usersByCity = User::select('city', \DB::raw('count(*) as total'))
        ->groupBy('city')
        ->get();
 
    // Fetch monthly ad postings data
        $monthlyAds = Ad::select(\DB::raw('COUNT(*) as count'), \DB::raw('MONTHNAME(created_at) as month'))
                        ->where('status', 'approved')
                        ->groupBy('month')
                        ->orderBy(\DB::raw('MIN(created_at)'), 'ASC')
                        ->get();

        $totalUsers = User::count();
        $approvedAdsCount = Ad::where('status', 'approved')->count();
        $rejectedAdsCount = Ad::where('status', 'rejected')->count();
        $pendingAdsCount = Ad::where('status', 'pending')->count();

        $adCost = 10; // cost per ad
        $totalRevenue = $approvedAdsCount * $adCost;
    
        
        // Fetch monthly registered users data
        $monthlyUsers = User::select(\DB::raw('COUNT(*) as count'), \DB::raw('MONTHNAME(created_at) as month'))
        ->groupBy('month')
        ->orderBy(\DB::raw('MIN(created_at)'), 'ASC')
        ->get();

        // Convert to arrays for use in the chart
        $monthlyUserCounts = $monthlyUsers->pluck('count');
        $monthlyUserLabels = $monthlyUsers->pluck('month');

        return view('admin.dashboard', compact('categories', 'adsByCity', 'usersByCity', 'monthlyAds', 'totalUsers', 'approvedAdsCount', 'totalRevenue', 'monthlyUserCounts', 'monthlyUserLabels', 'rejectedAdsCount', 'pendingAdsCount'));
    }

    public function showAdvertisementDashboard()
    {
        $categories = Category::withCount('ads')->get();
        $adsByCity = Ad::select('city', \DB::raw('count(*) as total'))
                        ->groupBy('city')
                        ->get();

        $usersByCity = User::select('city', \DB::raw('count(*) as total'))
        ->groupBy('city')
        ->get();
 
    // Fetch monthly ad postings data
        $monthlyAds = Ad::select(\DB::raw('COUNT(*) as count'), \DB::raw('MONTHNAME(created_at) as month'))
                        ->where('status', 'approved')
                        ->groupBy('month')
                        ->orderBy(\DB::raw('MIN(created_at)'), 'ASC')
                        ->get();

        $totalUsers = User::count();
        $approvedAdsCount = Ad::where('status', 'approved')->count();

        $adCost = 10; // cost per ad
        $totalRevenue = $approvedAdsCount * $adCost;

        $monthlyApprovedAds = Ad::select(\DB::raw('COUNT(*) as count'), \DB::raw('MONTHNAME(created_at) as month'))
        ->where('status', 'approved')
        ->groupBy('month')
        ->orderBy(\DB::raw('MIN(created_at)'), 'ASC')
        ->pluck('count', 'month')
        ->toArray();

        // Fetch monthly ad postings data for rejected ads
        $monthlyRejectedAds = Ad::select(\DB::raw('COUNT(*) as count'), \DB::raw('MONTHNAME(created_at) as month'))
            ->where('status', 'rejected')
            ->groupBy('month')
            ->orderBy(\DB::raw('MIN(created_at)'), 'ASC')
            ->pluck('count', 'month')
            ->toArray();

        // Prepare data for the chart
        $months = array_unique(array_merge(array_keys($monthlyApprovedAds), array_keys($monthlyRejectedAds)));
        $approvedCounts = array_map(fn($month) => $monthlyApprovedAds[$month] ?? 0, $months);
        $rejectedCounts = array_map(fn($month) => $monthlyRejectedAds[$month] ?? 0, $months);

        return view('admin.advertisement_dashboard', compact('totalUsers', 'approvedAdsCount', 'monthlyAds', 'categories', 'adsByCity', 'totalRevenue','months', 'approvedCounts', 'rejectedCounts'));
    }

    public function revenue()
    {

        $totalUsers = User::count();
        $approvedAdsCount = Ad::where('status', 'approved')->count();

        $adCost = 10; // cost per ad
        $totalRevenue = $approvedAdsCount * $adCost;

        // Fetch monthly revenue data
        $monthlyRevenue = Ad::select(
            \DB::raw('SUM(CASE WHEN status = "approved" THEN 10 ELSE 0 END) as revenue'),
            \DB::raw('MONTHNAME(created_at) as month'))
            ->groupBy('month')
            ->orderBy(\DB::raw('MIN(created_at)'), 'ASC')
            ->get();

        // Prepare data for the chart
        $revenueData = $monthlyRevenue->pluck('revenue');
        $monthLabels = $monthlyRevenue->pluck('month');

        return view('admin.revenue', compact('revenueData', 'monthLabels','totalUsers','approvedAdsCount','totalRevenue'));
    }



    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }



    public function users(Request $request)
    {
    $search = $request->input('search');

    if ($search) {
        $users = User::where('id', $search)
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->get();
    }  else {

        $users = User::all();
    }

    return view('admin.users.index', compact('users'));
    }

    public function index()
    {
        $ads = Ad::where('status', 'pending')->get();
        return view('admin.ads.index', compact('ads'));
    }

    public function approve($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->status = 'approved';
        $ad->save();

        return redirect()->route('admin.ads.index')->with('success', 'Ad approved successfully.');
    }

    public function reject($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->status = 'rejected';
        $ad->save();

        return redirect()->route('admin.ads.index')->with('success', 'Ad rejected successfully.');
    }


    public function approvedAds(Request $request)
    {
        $query = Ad::where('status', 'approved');
    
        // Search for ads by ad name, user name, or city
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('topic', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                  })
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }
    
        // Filter by category
        if ($request->filled('category_id')) {
            $category_id = $request->input('category_id');
            $query->where('category_id', $category_id);
        }
    
        $ads = $query->get();
        $categories = Category::all(); // Fetch categories for the filter dropdown
    
        return view('admin.ads.approved', compact('ads', 'categories'));
    }

    public function showReviews($adId)
    {
        $ad = Ad::findOrFail($adId);
        $ratings = $ad->ratings()->with('user')->get();

        return view('admin.ads.reviews', compact('ad', 'ratings'));
    }


public function deleteRating($ratingId)
{
    $rating = Rating::findOrFail($ratingId);
    $rating->delete();

    return back()->with('success', 'Rating deleted successfully.');
}

    

}

