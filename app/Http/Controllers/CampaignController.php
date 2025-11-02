<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Program;
use App\Models\ZakatPayment;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    /**
     * Display a listing of all campaigns without category filtering.
     */
    public function all()
    {
        // Delete expired campaigns before fetching
        $this->deleteExpiredCampaigns();
        
        $campaigns = Campaign::published()
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate total collected and target amounts
        $totalCollected = 0;
        $totalTarget = 0;

        foreach ($campaigns as $campaign) {
            // Calculate collected amount for each campaign (now using dynamic calculation)
            $collected = $campaign->collected_amount;
            // Add to campaign object as a custom attribute for the view
            $campaign->display_collected_amount = $collected;
            $totalCollected += $collected;
            $totalTarget += $campaign->target_amount;
        }

        return view('campaigns.all', compact('campaigns', 'totalCollected', 'totalTarget'));
    }

    /**
     * Display a listing of campaigns by program category.
     */
    public function index($category)
    {
        // Delete expired campaigns before fetching
        $this->deleteExpiredCampaigns();
        
        $campaigns = Campaign::published()
            ->byCategory($category)
            ->orderBy('created_at', 'desc')
            ->get();

        // Update collected amounts dynamically
        foreach ($campaigns as $campaign) {
            $campaign->display_collected_amount = $campaign->collected_amount;
        }

        // Get program for this category
        $program = Program::byCategory($category)->first();
        $totalCollected = 0;
        $totalTarget = 0;

        if ($program) {
            $totalCollected = $program->total_collected;
            $totalTarget = $program->total_target;
        } else {
            // Fallback to direct calculation if program doesn't exist
            $totalCollected = $campaigns->sum(function ($campaign) {
                return $campaign->display_collected_amount;
            });
            $totalTarget = $campaigns->sum('target_amount');
        }

        $categoryDetails = $this->getCategoryDetails($category);

        return view('campaigns.index', compact('campaigns', 'category', 'categoryDetails', 'totalCollected', 'totalTarget'));
    }

    /**
     * Display the specified campaign.
     */
    public function show($category, Campaign $campaign)
    {
        // Check if campaign is expired and delete if so
        if ($campaign->isExpired() && $campaign->status === 'published') {
            if ($campaign->photo) {
                Storage::disk('public')->delete($campaign->photo);
            }
            $campaign->delete();
            abort(404, 'Campaign sudah berakhir dan telah dihapus.');
        }

        // Load related payments for this campaign and update collected amount
        $campaign->load('zakatPayments.muzakki');
        $campaign->display_collected_amount = $campaign->collected_amount;

        // Get program for this category
        $program = Program::byCategory($category)->first();
        $totalCollected = 0;

        if ($program) {
            $totalCollected = $program->total_collected;
        } else {
            // Fallback to direct calculation
            $totalCollected = Campaign::published()->byCategory($category)->get()->sum(function ($campaign) {
                return $campaign->collected_amount;
            });
        }

        $categoryDetails = $this->getCategoryDetails($category);

        return view('campaigns.show', compact('campaign', 'category', 'categoryDetails', 'totalCollected'));
    }
    /**
     * Store a newly created campaign in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'program_category' => 'required|string|max:50',
            'target_amount' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,completed,cancelled',
            'end_date' => 'nullable|date|after:today'
        ]);

        $data = $request->only([
            'title',
            'description',
            'program_category',
            'target_amount',
            'status',
            'end_date'
        ]);

        // Set collected_amount to 0 for new campaigns
        $data['collected_amount'] = 0;

        // Associate with program if exists
        $program = Program::byCategory($request->program_category)->first();
        if ($program) {
            $data['program_id'] = $program->id;
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('campaigns', 'public');
        }

        $campaign = Campaign::create($data);

        return redirect()->back()->with('success', 'Campaign created successfully.');
    }

    // Admin methods

    /**
     * Display a listing of all campaigns for admin.
     */
    public function adminIndex()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();

        // Update collected amounts dynamically
        foreach ($campaigns as $campaign) {
            $campaign->display_collected_amount = $campaign->zakatPayments()->sum('paid_amount');
        }

        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function adminCreate()
    {
        $programs = Program::active()->get();
        return view('admin.campaigns.create', compact('programs'));
    }

    /**
     * Store a newly created campaign in storage from admin panel.
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'program_category' => 'required|string|max:50',
            'program_id' => 'nullable|exists:programs,id',
            'target_amount' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,completed,cancelled',
            'end_date' => 'nullable|date|after:today'
        ]);

        $data = $request->only([
            'title',
            'description',
            'program_category',
            'program_id',
            'target_amount',
            'status',
            'end_date'
        ]);

        // Set collected_amount to 0 for new campaigns
        $data['collected_amount'] = 0;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('campaigns', 'public');
        }

        $campaign = Campaign::create($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created successfully.');
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function adminEdit(Campaign $campaign)
    {
        // Don't overwrite collected amount - allow manual editing
        // $campaign->collected_amount = $campaign->zakatPayments()->sum('paid_amount');
        $programs = Program::active()->get();

        return view('admin.campaigns.edit', compact('campaign', 'programs'));
    }

    /**
     * Update the specified campaign in storage.
     */
    public function adminUpdate(Request $request, Campaign $campaign)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'program_category' => 'required|string|max:50',
            'program_id' => 'nullable|exists:programs,id',
            'target_amount' => 'required|numeric|min:0',
            // Remove collected_amount from validation as it should be readonly
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,completed,cancelled',
            'end_date' => 'nullable|date|after:today'
        ]);

        $data = $request->only([
            'title',
            'description',
            'program_category',
            'program_id',
            'target_amount',
            'status',
            'end_date'
        ]);

        // Ensure collected_amount remains unchanged (readonly)
        // $data['collected_amount'] = $campaign->collected_amount;

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($campaign->photo) {
                Storage::disk('public')->delete($campaign->photo);
            }
            $data['photo'] = $request->file('photo')->store('campaigns', 'public');
        }

        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function adminDestroy(Campaign $campaign)
    {
        // Delete photo if exists
        if ($campaign->photo) {
            Storage::disk('public')->delete($campaign->photo);
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted successfully.');
    }

    /**
     * Get category details for display
     */
    private function getCategoryDetails($category)
    {
        $categories = [
            'pendidikan' => [
                'title' => 'Pendidikan',
                'subtitle' => 'Meningkatkan kualitas pendidikan melalui berbagai inisiatif',
                'image' => asset('img/program/pendidikan.jpg'),
                'text_color' => 'text-blue-800',
                'bg_color' => 'bg-blue-100',
                'border_color' => 'border-blue-200'
            ],
            'kesehatan' => [
                'title' => 'Kesehatan',
                'subtitle' => 'Memberikan akses layanan kesehatan yang terjangkau',
                'image' => asset('img/program/kesehatan.jpg'),
                'text_color' => 'text-green-800',
                'bg_color' => 'bg-green-100',
                'border_color' => 'border-green-200'
            ],
            'ekonomi' => [
                'title' => 'Ekonomi',
                'subtitle' => 'Mendorong kemandirian ekonomi masyarakat',
                'image' => asset('img/program/ekonomi.jpg'),
                'text_color' => 'text-amber-800',
                'bg_color' => 'bg-amber-100',
                'border_color' => 'border-amber-200'
            ],
            'sosial-dakwah' => [
                'title' => 'Sosial & Dakwah',
                'subtitle' => 'Mengembangkan kegiatan sosial dan dakwah',
                'image' => asset('img/program/sosial-dakwah.jpg'),
                'text_color' => 'text-purple-800',
                'bg_color' => 'bg-purple-100',
                'border_color' => 'border-purple-200'
            ],
            'kemanusiaan' => [
                'title' => 'Kemanusiaan',
                'subtitle' => 'Menyejahterakan umat manusia tanpa diskriminasi',
                'image' => asset('img/program/kemanusiaan.jpg'),
                'text_color' => 'text-purple-800',
                'bg_color' => 'bg-purple-100',
                'border_color' => 'border-purple-200'
            ],
            'lingkungan' => [
                'title' => 'Lingkungan',
                'subtitle' => 'Menjaga lingkungan untuk generasi mendatang',
                'image' => asset('img/program/lingkungan.jpg'),
                'text_color' => 'text-cyan-800',
                'bg_color' => 'bg-cyan-100',
                'border_color' => 'border-cyan-200'
            ]
        ];

        return $categories[$category] ?? [
            'title' => ucfirst($category),
            'subtitle' => 'Program ' . ucfirst($category),
            'image' => asset('img/masjid.webp'),
            'text_color' => 'text-emerald-800',
            'bg_color' => 'bg-emerald-100',
            'border_color' => 'border-emerald-200'
        ];
    }

    /**
     * Delete expired campaigns
     */
    private function deleteExpiredCampaigns()
    {
        $expiredCampaigns = Campaign::where('status', 'published')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay())
            ->get();

        foreach ($expiredCampaigns as $campaign) {
            try {
                // Delete photo if exists
                if ($campaign->photo) {
                    Storage::disk('public')->delete($campaign->photo);
                }
                
                // Delete the campaign
                $campaign->delete();
            } catch (\Exception $e) {
                // Log error but don't break the request
                \Log::error("Failed to delete expired campaign", [
                    'campaign_id' => $campaign->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function showPersonalCampaign($email)
    {
        // Cari muzakki berdasarkan email
        $muzakki = \App\Models\Muzakki::where('email', $email)->first();

        if (!$muzakki) {
            abort(404, 'Campaigner tidak ditemukan');
        }

        // Ambil semua campaign yang dibuat oleh muzakki ini
        $campaigns = \App\Models\Campaign::where('created_by', $muzakki->user_id)
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('campaigns.personal', compact('muzakki', 'campaigns'));
    }
}
