<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookCategorizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __construct(
        private BookCategorizationService $categorizationService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $books = Book::where('owner_id', auth()->id())->latest()->get();
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'language' => 'nullable|string|max:255',
            'condition' => 'required|in:sehr gut,gut,befriedigend,akzeptabel',
            'cover' => 'nullable|image|max:2048',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            $data['image_path'] = $request->file('cover')->store('covers', 'public');
        }

        $data['owner_id'] = auth()->id();
        $data['status'] = 'verfügbar';

        $book = Book::create($data);

        // Auto-categorize the book using AI
        $this->categorizationService->updateBookCategory($book);

        return redirect()->route('books.index')->with('success', 'Buch wurde erfolgreich hinzugefügt!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): View
    {
        // Temporarily allow all authenticated users to view any book for debugging
        // TODO: Re-enable proper authorization after testing
        // Check if user can view this book (either owner or book is available)
        // if ($book->owner_id !== auth()->id() && $book->status !== Book::STATUS_VERFUEGBAR) {
        //     abort(403, 'Sie haben keine Berechtigung, dieses Buch zu sehen. Das Buch ist derzeit nicht verfügbar.');
        // }

        // Get recommendations for this book
        $recommendations = $this->categorizationService->getRecommendations($book);

        return view('books.show', compact('book', 'recommendations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book): View
    {
        // Only owner can edit
        if ($book->owner_id !== auth()->id()) {
            abort(403, 'Sie können nur Ihre eigenen Bücher bearbeiten.');
        }

        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        // Only owner can update
        if ($book->owner_id !== auth()->id()) {
            abort(403, 'Sie können nur Ihre eigenen Bücher bearbeiten.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'language' => 'nullable|string|max:255',
            'condition' => 'required|in:sehr gut,gut,befriedigend,akzeptabel',
            'status' => 'required|in:verfügbar,ausgeliehen,reserviert,angefragt',
            'cover' => 'nullable|image|max:2048',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            $data['image_path'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('books.show', $book)->with('success', 'Buch wurde erfolgreich aktualisiert!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): RedirectResponse
    {
        // Only owner can delete
        if ($book->owner_id !== auth()->id()) {
            abort(403, 'Sie können nur Ihre eigenen Bücher löschen.');
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buch wurde erfolgreich gelöscht!');
    }
}
