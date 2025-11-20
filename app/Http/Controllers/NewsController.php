<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of news articles
     */
    public function index()
    {
        // For public berita page
        $news = News::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('pages.berita', compact('news'));
    }

    /**
     * Display a listing of news articles for admin
     */
    public function adminIndex()
    {
        $news = News::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    /**
     * Display the specified news article for admin
     */
    public function adminShow(News $news)
    {
        $news->load('author');
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for creating a new news article
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created news article
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
        $validated['slug'] = News::generateSlug($validated['title']);
        $validated['author_id'] = Auth::id();
        $validated['is_published'] = $request->has('is_published');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
            $validated['image'] = $imagePath;
        }

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified news article
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified news article
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'excerpt' => 'nullable|string|max:500',
            'is_published' => 'boolean'
        ]);

        // Update slug if title changed
        if ($news->title !== $validated['title']) {
            $validated['slug'] = News::generateSlug($validated['title']);
        }

        $validated['is_published'] = $request->has('is_published');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $imagePath = $request->file('image')->store('news', 'public');
            $validated['image'] = $imagePath;
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Remove the specified news article
     */
    public function destroy(News $news)
    {
        // Delete associated image
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus!');
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(News $news)
    {
        $news->update([
            'is_published' => !$news->is_published
        ]);

        $status = $news->is_published ? 'dipublikasikan' : 'di-draft';

        return back()->with('success', "Berita berhasil {$status}!");
    }

    /**
     * Display published news for public (for berita page)
     */
    public function publicIndex()
    {
        $news = News::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('partials.berita', compact('news'));
    }

    /**
     * Show all news with pagination
     */
    public function all()
    {
        $news = News::published()
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('news.all', compact('news'));
    }

    /**
     * Show single news article for public
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        return view('news.show', compact('news'));
    }
}
