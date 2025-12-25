<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Models\Category;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        $totalServiceProviders = ServiceProvider::count();
        $totalCategories = Category::count();
        $recentServiceProviders = ServiceProvider::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Stats per kategori untuk semua user
        $categoriesWithCount = Category::withCount('serviceProviders')
            ->orderBy('name')
            ->get();

        // Booking statistics (hanya untuk admin)
        if (auth()->user()->isAdmin()) {
            $totalBookings = Booking::count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            $confirmedBookings = Booking::where('status', 'confirmed')->count();
            $completedBookings = Booking::where('status', 'completed')->count();
            
            // Recent bookings untuk admin
            $recentBookings = Booking::with(['user', 'serviceProvider', 'category'])
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard.index', compact(
                'totalServiceProviders',
                'totalCategories',
                'recentServiceProviders',
                'categoriesWithCount',
                'totalBookings',
                'pendingBookings',
                'confirmedBookings',
                'completedBookings',
                'recentBookings'
            ));
        } else {
            // User hanya melihat data umum (tanpa booking stats)
            return view('dashboard.index', compact(
                'totalServiceProviders',
                'totalCategories',
                'recentServiceProviders',
                'categoriesWithCount'
            ));
        }
    }
}
