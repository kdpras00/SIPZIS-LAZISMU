<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artikels = Artikel::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.artikel.index', compact('artikels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.artikel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'excerpt' => 'nullable|string|max:500',
            'is_published' => 'boolean'
        ]);

        // Generate slug
        $validated['slug'] = Artikel::generateSlug($validated['title']);
        $validated['author_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('artikel', 'public');
            $validated['image'] = $imagePath;
        }

        Artikel::create($validated);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Artikel $artikel)
    {
        $artikel->load('author');
        return view('admin.artikel.show', compact('artikel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artikel $artikel)
    {
        return view('admin.artikel.edit', compact('artikel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artikel $artikel)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'excerpt' => 'nullable|string|max:500',
            'is_published' => 'boolean'
        ]);

        // Update slug if title changed
        if ($artikel->title !== $validated['title']) {
            $validated['slug'] = Artikel::generateSlug($validated['title']);
        }

        $validated['is_published'] = $request->has('is_published');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($artikel->image) {
                Storage::disk('public')->delete($artikel->image);
            }

            $imagePath = $request->file('image')->store('artikel', 'public');
            $validated['image'] = $imagePath;
        }

        $artikel->update($validated);

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artikel $artikel)
    {
        // Delete associated image
        if ($artikel->image) {
            Storage::disk('public')->delete($artikel->image);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(Artikel $artikel)
    {
        $artikel->update([
            'is_published' => !$artikel->is_published
        ]);

        $status = $artikel->is_published ? 'dipublikasikan' : 'di-draft';
        
        return back()->with('success', "Artikel berhasil {$status}!");
    }

    /**
     * Display published articles for public
     */
    public function publicIndex()
    {
        $artikels = Artikel::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('artikel.index', compact('artikels'));
    }

    /**
     * Show single article for public
     */
    public function publicShow($slug)
    {
        $artikel = Artikel::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        return view('artikel.show', compact('artikel'));
    }
}