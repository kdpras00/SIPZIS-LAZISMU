<?php

namespace App\Http\Controllers;

use App\Models\Muzakki;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MuzakkiController extends Controller
{
    // Middleware is applied in routes

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Muzakki::with('user');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by occupation
        if ($request->has('occupation') && $request->occupation != '') {
            $query->where('occupation', $request->occupation);
        }

        // Filter by city
        if ($request->has('city') && $request->city != '') {
            $query->where('city', 'like', "%{$request->city}%");
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $muzakki = $query->latest()->paginate(15)->withQueryString();

        // Get filter options
        $occupations = Muzakki::select('occupation')->distinct()->whereNotNull('occupation')->pluck('occupation');
        $cities = Muzakki::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('muzakki.index', compact('muzakki', 'occupations', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('muzakki.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:muzakki,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|digits:5',
            'occupation' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'country' => 'required|string|max:255', // Add country validation
            'bio' => 'nullable|string', // Add bio validation
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add profile photo validation
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add KTP photo validation
            'create_user_account' => 'boolean',
            'password' => 'required_if:create_user_account,1|nullable|string|min:8',
        ], [
            'postal_code.digits' => 'Kode pos harus terdiri dari 5 digit angka.',
        ]);

        $user = null;

        // Create user account if requested
        if ($request->create_user_account && $request->email) {
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'muzakki',
                'is_active' => true,
                'phone' => $request->phone,
            ]);
        }

        // Handle location data - use names instead of IDs
        $country = $request->country;
        $province = $request->province_name ?? $request->province;
        $city = $request->city_name ?? $request->city;
        $district = $request->district_name ?? $request->district;
        $village = $request->village_name ?? $request->village;

        // Handle file uploads
        $profilePhotoPath = null;
        $ktpPhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if ($request->hasFile('ktp_photo')) {
            $ktpPhotoPath = $request->file('ktp_photo')->store('ktp_photos', 'public');
        }

        // Generate campaign URL if email exists
        $campaignUrl = $request->email ? url('/campaigner/' . $request->email) : null;

        $muzakki = Muzakki::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $city,
            'province' => $province,
            'district' => $district,
            'village' => $village,
            'postal_code' => $request->postal_code,
            'country' => $country, // Add country
            'campaign_url' => $campaignUrl, // Auto-generate campaign URL
            'profile_photo' => $profilePhotoPath, // Add profile photo
            'ktp_photo' => $ktpPhotoPath, // Add KTP photo
            'bio' => $request->bio, // Add bio
            'occupation' => $request->occupation,
            'date_of_birth' => $request->date_of_birth,
            'user_id' => $user?->id,
            'is_active' => true,
        ]);

        return redirect()->route('muzakki.index')->with('success', 'Data muzakki berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Muzakki $muzakki)
    {
        $muzakki->load(['user', 'zakatPayments.programType']);

        $stats = [
            'total_zakat' => $muzakki->zakatPayments()->completed()->sum('paid_amount'),
            'payment_count' => $muzakki->zakatPayments()->completed()->count(),
            'last_payment' => $muzakki->zakatPayments()->completed()->latest('payment_date')->first(),
        ];

        $recentPayments = $muzakki->zakatPayments()
            ->with('programType')
            ->completed()
            ->latest('payment_date')
            ->take(10)
            ->get();

        return view('muzakki.show', compact('muzakki', 'stats', 'recentPayments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Muzakki $muzakki = null)
    {
        // If no muzakki is provided, get current user's muzakki profile (for profile editing)
        if (!$muzakki) {
            $user = Auth::user();
            $muzakki = $user->muzakki;

            // If muzakki profile doesn't exist, create it
            if (!$muzakki) {
                $muzakki = Muzakki::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'user_id' => $user->id,
                    'is_active' => $user->is_active ?? true,
                    'campaign_url' => url('/campaigner/' . $user->email),
                ]);
            }
        }

        return view('muzakki.edit', compact('muzakki'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Muzakki $muzakki = null)
    {
        // If no muzakki is provided, get current user's muzakki profile
        if (!$muzakki) {
            $muzakki = Auth::user()->muzakki;
            if (!$muzakki) {
                abort(404, 'Profil muzakki tidak ditemukan.');
            }
        }

        // Check if this is a password-only update
        if ($request->has('current_password') && $request->has('new_password')) {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, Auth::user()->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }

            Auth::user()->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with('success', 'Password berhasil diperbarui.');
        }

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required', // Make email required
                'email',
                Rule::unique('muzakki')->ignore($muzakki->id),
                Rule::unique('users')->ignore($muzakki->user_id),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($muzakki->user_id),
            ],
            'nik' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('muzakki')->ignore($muzakki->id),
            ],
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'occupation' => 'nullable|string|max:100',
            'monthly_income' => 'nullable|numeric|min:0',
            'date_of_birth' => 'nullable|date',
            'bio' => 'nullable|string',
            'is_active' => 'boolean',
            'country' => 'nullable|string|max:255',
            'campaign_url' => 'nullable|url|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add profile photo validation
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add KTP photo validation
        ];

        $request->validate($rules, [
            'postal_code.max' => 'Kode pos maksimal 10 karakter.',
        ]);

        // Prepare update data - START WITH EXISTING DATA
        $updateData = $muzakki->toArray();

        // Update only fields that are present in request
        $updateData['name'] = $request->name;
        $updateData['email'] = $request->email;
        $updateData['phone'] = $request->phone;
        $updateData['gender'] = $request->gender;
        $updateData['address'] = $request->address;
        $updateData['occupation'] = $request->occupation;
        $updateData['bio'] = $request->bio;
        $updateData['is_active'] = $request->is_active ?? $muzakki->is_active;

        // Handle file uploads
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $updateData['profile_photo'] = $profilePhotoPath;
        }

        if ($request->hasFile('ktp_photo')) {
            $ktpPhotoPath = $request->file('ktp_photo')->store('ktp_photos', 'public');
            $updateData['ktp_photo'] = $ktpPhotoPath;
        }

        // Handle country - prefer country_name from hidden input
        if ($request->filled('country_name')) {
            $updateData['country'] = $request->country_name;
        } elseif ($request->filled('country')) {
            $updateData['country'] = $request->country;
        }
        // If still null, set default to Indonesia
        if (empty($updateData['country'])) {
            $updateData['country'] = 'Indonesia';
        }

        // Handle campaign_url
        if ($request->filled('campaign_url')) {
            $updateData['campaign_url'] = $request->campaign_url;
        } else {
            // Auto-generate campaign URL if email exists
            if (!empty($updateData['email'])) {
                $updateData['campaign_url'] = url('/campaigner/' . $updateData['email']);
            }
        }

        // Handle date of birth
        if ($request->filled('date_of_birth')) {
            $updateData['date_of_birth'] = $request->date_of_birth;
        }

        // Handle phone verification - THIS IS CRITICAL
        if ($request->has('phone_verified')) {
            $updateData['phone_verified'] = (int)$request->phone_verified;
        }

        // Handle location data - use names from hidden inputs
        if ($request->filled('province_name')) {
            $updateData['province'] = $request->province_name;
        } elseif ($request->filled('province')) {
            $updateData['province'] = $request->province;
        }

        if ($request->filled('city_name')) {
            $updateData['city'] = $request->city_name;
        } elseif ($request->filled('city')) {
            $updateData['city'] = $request->city;
        }

        if ($request->filled('district_name')) {
            $updateData['district'] = $request->district_name;
        } elseif ($request->filled('district')) {
            $updateData['district'] = $request->district;
        }

        if ($request->filled('village_name')) {
            $updateData['village'] = $request->village_name;
        } elseif ($request->filled('village')) {
            $updateData['village'] = $request->village;
        }

        if ($request->filled('postal_code')) {
            $updateData['postal_code'] = $request->postal_code;
        }

        // Remove timestamps from update data
        unset($updateData['created_at'], $updateData['updated_at']);

        // Log the update data for debugging
        \Illuminate\Support\Facades\Log::info('Muzakki Update Data:', $updateData);

        // Update muzakki record
        $muzakki->update($updateData);

        // Handle user account if needed
        if ($muzakki->user) {
            $muzakki->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_active' => $request->is_active ?? true,
            ]);
        }

        // Redirect based on context
        if (request()->route()->hasParameter('muzakki')) {
            return redirect()->route('muzakki.index')->with('success', 'Data muzakki berhasil diperbarui.');
        } else {
            return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Muzakki $muzakki)
    {
        // Check if muzakki has payments
        if ($muzakki->zakatPayments()->count() > 0) {
            return redirect()->route('muzakki.index')->with('error', 'Muzakki tidak dapat dihapus karena sudah memiliki riwayat pembayaran zakat.');
        }

        // Delete related user account if exists
        if ($muzakki->user) {
            $muzakki->user->delete();
        }

        $muzakki->delete();

        return redirect()->route('muzakki.index')->with('success', 'Data muzakki berhasil dihapus.');
    }

    /**
     * Toggle muzakki status
     */
    public function toggleStatus(Muzakki $muzakki)
    {
        $muzakki->update(['is_active' => !$muzakki->is_active]);

        // Update related user account if exists
        if ($muzakki->user) {
            $muzakki->user->update(['is_active' => $muzakki->is_active]);
        }

        $status = $muzakki->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('muzakki.index')->with('success', "Muzakki berhasil {$status}.");
    }

    /**
     * AJAX search endpoint for real-time search
     */
    public function search(Request $request)
    {
        $query = Muzakki::with('user');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by occupation
        if ($request->has('occupation') && $request->occupation != '') {
            $query->where('occupation', $request->occupation);
        }

        // Filter by city
        if ($request->has('city') && $request->city != '') {
            $query->where('city', 'like', "%{$request->city}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $muzakki = $query->latest()->paginate(15);

        // Calculate statistics
        $totalCount = Muzakki::count();
        $activeCount = Muzakki::where('is_active', true)->count();
        $inactiveCount = Muzakki::where('is_active', false)->count();
        $thisMonthCount = Muzakki::where('created_at', '>=', now()->startOfMonth())->count();

        return response()->json([
            'success' => true,
            'data' => [
                'muzakki' => $muzakki->items(),
                'pagination' => [
                    'current_page' => $muzakki->currentPage(),
                    'last_page' => $muzakki->lastPage(),
                    'per_page' => $muzakki->perPage(),
                    'total' => $muzakki->total(),
                    'from' => $muzakki->firstItem(),
                    'to' => $muzakki->lastItem(),
                ],
                'statistics' => [
                    'total' => $totalCount,
                    'active' => $activeCount,
                    'inactive' => $inactiveCount,
                    'this_month' => $thisMonthCount,
                ],
            ]
        ]);
    }
}
