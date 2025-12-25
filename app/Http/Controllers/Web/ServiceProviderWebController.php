<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ServiceProvider;
use App\Models\Category;

class ServiceProviderWebController extends Controller
{
    /**
     * Display a listing of service providers
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::with('category');
        
        // Filter by category if provided
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        $serviceProviders = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('service-providers.index', compact('serviceProviders', 'categories'));
    }

    /**
     * Show the form for creating a new service provider
     */
    public function create()
    {
        $categories = Category::all();
        return view('service-providers.create', compact('categories'));
    }

    /**
     * Store a newly created service provider
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|regex:/^[0-9+\-\(\)\s]+$/|min:10|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ], [
            'name.required' => 'Nama service provider wajib diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'name.max' => 'Nama maksimal 100 karakter',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'phone.regex' => 'Format nomor telepon tidak valid (hanya angka, +, -, (, ), dan spasi)',
            'phone.min' => 'Nomor telepon minimal 10 digit',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',
            'email.email' => 'Format email tidak valid',
            'address.max' => 'Alamat maksimal 500 karakter',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus: JPEG, PNG, JPG, GIF, atau WEBP',
            'photo.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        $data = $request->only(['name', 'category_id', 'phone', 'email', 'address', 'description']);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('service-providers', 'public');
            $data['photo'] = $photoPath;
        }

        ServiceProvider::create($data);

        return redirect()
            ->route('service-providers.index')
            ->with('success', 'Service Provider berhasil ditambahkan');
    }

    /**
     * Display the specified service provider
     */
    public function show($uuid)
    {
        $provider = ServiceProvider::with('category')->where('uuid', $uuid)->firstOrFail();
        return view('service-providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified service provider
     */
    public function edit($uuid)
    {
        $provider = ServiceProvider::where('uuid', $uuid)->firstOrFail();
        $categories = Category::all();
        return view('service-providers.edit', compact('provider', 'categories'));
    }

    /**
     * Update the specified service provider
     */
    public function update(Request $request, $uuid)
    {
        $provider = ServiceProvider::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|regex:/^[0-9+\-\(\)\s]+$/|min:10|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ], [
            'name.required' => 'Nama service provider wajib diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'name.max' => 'Nama maksimal 100 karakter',
            'category_id.required' => 'Kategori wajib dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'phone.regex' => 'Format nomor telepon tidak valid (hanya angka, +, -, (, ), dan spasi)',
            'phone.min' => 'Nomor telepon minimal 10 digit',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',
            'email.email' => 'Format email tidak valid',
            'address.max' => 'Alamat maksimal 500 karakter',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus: JPEG, PNG, JPG, GIF, atau WEBP',
            'photo.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        $data = $request->only(['name', 'category_id', 'phone', 'email', 'address', 'description']);

        if ($request->hasFile('photo')) {
            if ($provider->photo && Storage::disk('public')->exists($provider->photo)) {
                Storage::disk('public')->delete($provider->photo);
            }
            $photoPath = $request->file('photo')->store('service-providers', 'public');
            $data['photo'] = $photoPath;
        }

        $provider->update($data);

        return redirect()
            ->route('service-providers.index')
            ->with('success', 'Service Provider berhasil diperbarui');
    }

    /**
     * Remove the specified service provider
     */
    public function destroy($uuid)
    {
        $provider = ServiceProvider::where('uuid', $uuid)->firstOrFail();

        if ($provider->photo && Storage::disk('public')->exists($provider->photo)) {
            Storage::disk('public')->delete($provider->photo);
        }

        $provider->delete();

        return redirect()
            ->route('service-providers.index')
            ->with('success', 'Service Provider berhasil dihapus');
    }
}
