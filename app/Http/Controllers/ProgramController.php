<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\ProgramType;
use App\Models\ZakatType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of programs for admin management.
     */
    public function adminIndex()
    {
        $programs = Program::with('programType')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $groupedPrograms = $programs->groupBy('category');

        return view('admin.programs.index', compact('groupedPrograms'));
    }

    /**
     * Show the form for creating a new program.
     */
    public function adminCreate()
    {
        $programTypes = ProgramType::active()->get();
        $categories = $this->getAvailableCategories();

        return view('admin.programs.create', compact('programTypes', 'categories'));
    }

    /**
     * Show the form for bulk creating programs.
     */
    public function adminBulkCreate()
    {
        $categories = $this->getAvailableCategories();

        return view('admin.programs.bulk-create', compact('categories'));
    }

    /**
     * Store a newly created program in storage.
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:zakat,infaq,shadaqah,pendidikan,kesehatan,ekonomi,sosial-dakwah,kemanusiaan,lingkungan',
            'target_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get target_amount and ensure it's numeric (remove any formatting)
        $targetAmount = $request->input('target_amount');
        if ($targetAmount !== null && $targetAmount !== '') {
            $targetAmount = str_replace(['.', ','], '', $targetAmount);
            $targetAmount = is_numeric($targetAmount) ? (float)$targetAmount : null;
        } else {
            $targetAmount = null;
        }

        $data = $request->only(['name', 'description', 'status']);
        $data['target_amount'] = $targetAmount;
        $data['category'] = $request->category; // Use category directly
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        // Cek duplikasi nama + kategori
        if (Program::where('name', $data['name'])->where('category', $data['category'])->exists()) {
            return redirect()->back()->withInput()
                ->withErrors(['name' => 'Program dengan nama dan kategori ini sudah ada.']);
        }

        // Upload foto jika ada
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('programs', 'public');
        }

        Program::create($data);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil ditambahkan.');
    }

    /**
     * Store multiple programs at once.
     */
    public function adminStoreBulk(Request $request)
    {
        $request->validate([
            'programs' => 'required|array',
            'programs.*.name' => 'required|string|max:255',
            'programs.*.category' => 'required|string|in:zakat,infaq,shadaqah,pendidikan,kesehatan,ekonomi,sosial-dakwah,kemanusiaan,lingkungan',
            'programs.*.target_amount' => 'nullable|numeric|min:0',
            'programs.*.status' => 'required|in:active,inactive',
        ]);

        foreach ($request->programs as $programData) {
            // Get target_amount and ensure it's numeric (remove any formatting)
            $targetAmount = $programData['target_amount'] ?? 0;
            if ($targetAmount && $targetAmount !== '') {
                $targetAmount = str_replace(['.', ','], '', $targetAmount);
                $targetAmount = is_numeric($targetAmount) ? (float)$targetAmount : 0;
            } else {
                $targetAmount = 0;
            }

            $data = [
                'name' => $programData['name'],
                'description' => $programData['description'] ?? '',
                'target_amount' => $targetAmount,
                'status' => $programData['status'],
                'category' => $programData['category'], // Use category directly
                'slug' => $this->generateUniqueSlug($programData['name']),
            ];

            // Cek duplikasi
            if (Program::where('name', $data['name'])->where('category', $data['category'])->exists()) {
                return redirect()->back()->withInput()
                    ->withErrors([
                        'programs' => 'Program "' . $data['name'] . '" dengan kategori "' . $data['category'] . '" sudah ada.'
                    ]);
            }

            Program::create($data);
        }

        return redirect()->route('admin.programs.index')
            ->with('success', 'Semua program berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified program.
     */
    public function adminEdit(Program $program)
    {
        $programTypes = ProgramType::active()->get();
        $categories = $this->getAvailableCategories();

        return view('admin.programs.edit', compact('program', 'programTypes', 'categories'));
    }

    /**
     * Update the specified program in storage.
     */
    public function adminUpdate(Request $request, Program $program)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:zakat,infaq,shadaqah,pendidikan,kesehatan,ekonomi,sosial-dakwah,kemanusiaan,lingkungan',
            'target_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get target_amount and ensure it's numeric (remove any formatting)
        $targetAmount = $request->input('target_amount');
        if ($targetAmount !== null && $targetAmount !== '') {
            $targetAmount = str_replace(['.', ','], '', $targetAmount);
            $targetAmount = is_numeric($targetAmount) ? (float)$targetAmount : null;
        } else {
            $targetAmount = null;
        }

        $data = $request->only(['name', 'description', 'status']);
        $data['target_amount'] = $targetAmount;
        $data['category'] = $request->category; // Use category directly
        $data['slug'] = $this->generateUniqueSlug($data['name'], $program->id);

        // Upload foto baru dan hapus yang lama
        if ($request->hasFile('photo')) {
            if ($program->photo) {
                Storage::disk('public')->delete($program->photo);
            }
            $data['photo'] = $request->file('photo')->store('programs', 'public');
        }

        $program->update($data);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil diperbarui.');
    }

    /**
     * Remove the specified program from storage.
     */
    public function adminDestroy(Program $program)
    {
        if ($program->photo) {
            Storage::disk('public')->delete($program->photo);
        }

        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil dihapus.');
    }

    /**
     * Display the specified program.
     */
    public function show($slug)
    {
        $program = Program::where('slug', $slug)->active()->firstOrFail();

        $zakatTypes = ZakatType::active()->get();
        $collectedAmount = $program->total_collected;
        $totalTarget = $program->total_target;
        $category = $program->category;

        // Always use the individual program view for the show method
        $viewName = 'programs.show';

        return view($viewName, compact('program', 'zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
    }

    /**
     * Get available categories for programs (main categories only).
     */
    private function getAvailableCategories(): array
    {
        return [
            'zakat' => 'Zakat',
            'infaq' => 'Infaq',
            'shadaqah' => 'Shadaqah',
            'pendidikan' => 'Pendidikan',
            'kesehatan' => 'Kesehatan',
            'ekonomi' => 'Ekonomi',
            'sosial-dakwah' => 'Sosial & Dakwah',
            'kemanusiaan' => 'Kemanusiaan',
            'lingkungan' => 'Lingkungan',
        ];
    }

    /**
     * Generate a unique slug for a program name.
     * If the slug already exists, append a number to make it unique.
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = Program::where('slug', $slug);
            
            // Exclude current program when updating
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                return $slug;
            }

            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
    }
}
