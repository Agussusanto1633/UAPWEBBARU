<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ServiceProvider;
use App\Models\Category;

class BookingController extends Controller
{
    /**
     * Display booking list for admin (all bookings)
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'serviceProvider', 'category'])
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Display user's own bookings
     */
    public function myBookings()
    {
        $bookings = Booking::with(['serviceProvider', 'category'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }

    /**
     * Show form to create booking
     */
    public function create(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->query('category_id');
        
        $serviceProviders = ServiceProvider::with('category')
            ->when($selectedCategory, function($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })
            ->get();

        return view('bookings.create', compact('categories', 'serviceProviders', 'selectedCategory'));
    }

    /**
     * Store booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_provider_id' => 'required|exists:service_providers,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'customer_name' => 'required|string|min:3|max:100',
            'customer_phone' => 'required|string|regex:/^[0-9+\-\(\)\s]+$/|min:10|max:20',
            'customer_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ], [
            'service_provider_id.required' => 'Service provider wajib dipilih',
            'booking_date.required' => 'Tanggal booking wajib diisi',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh kurang dari hari ini',
            'booking_time.required' => 'Waktu booking wajib diisi',
            'customer_name.required' => 'Nama lengkap wajib diisi',
            'customer_name.min' => 'Nama minimal 3 karakter',
            'customer_name.max' => 'Nama maksimal 100 karakter',
            'customer_phone.required' => 'Nomor telepon wajib diisi',
            'customer_phone.regex' => 'Format nomor telepon tidak valid (hanya angka, +, -, (, ), dan spasi)',
            'customer_phone.min' => 'Nomor telepon minimal 10 digit',
            'customer_phone.max' => 'Nomor telepon maksimal 20 karakter',
            'customer_address.max' => 'Alamat maksimal 500 karakter',
            'notes.max' => 'Catatan maksimal 500 karakter'
        ]);

        $serviceProvider = ServiceProvider::findOrFail($validated['service_provider_id']);

        Booking::create([
            'user_id' => auth()->id(),
            'service_provider_id' => $validated['service_provider_id'],
            'category_id' => $serviceProvider->category_id,
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.my')->with('success', 'Booking berhasil dibuat! Menunggu konfirmasi.');
    }

    /**
     * Show booking detail
     */
    public function show(Booking $booking)
    {
        // Check if user is admin or owner of the booking
        if (!auth()->user()->isAdmin() && $booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $booking->load(['user', 'serviceProvider', 'category']);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Update booking status (admin only)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status booking berhasil diupdate.');
    }

    /**
     * Cancel booking (user can cancel their own pending bookings)
     */
    public function cancel(Booking $booking)
    {
        // Check if user owns this booking and status is pending
        if ($booking->user_id !== auth()->id() || $booking->status !== 'pending') {
            abort(403, 'Unauthorized or booking cannot be cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.my')->with('success', 'Booking berhasil dibatalkan.');
    }
}
