<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookCategorizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __construct(
        private BookCategorizationService $categorizationService
    ) {}

    /**
     * نمایش فهرست کتاب‌های کاربر
     */
    public function index(): View
    {
        $books = Book::where('owner_id', Auth::id())->latest()->get();
        return view('books.index', compact('books'));
    }

    /**
     * نمایش فرم ایجاد کتاب جدید
     */
    public function create(): View
    {
        return view('books.create');
    }

    /**
     * ذخیره کتاب جدید در دیتابیس
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

        // آپلود تصویر جلد کتاب
        if ($request->hasFile('cover')) {
            $data['image_path'] = $request->file('cover')->store('covers', 'public');
        }

        $data['owner_id'] = Auth::id();
        $data['status'] = 'verfügbar';

        $book = Book::create($data);

        // دسته‌بندی خودکار کتاب با استفاده از هوش مصنوعی
        $this->categorizationService->updateBookCategory($book);

        return redirect()->route('books.index')->with('success', 'کتاب با موفقیت اضافه شد!');
    }

    /**
     * نمایش جزئیات کتاب مشخص
     */
    public function show(Book $book): View
    {
        // موقتاً همه کاربران احراز هویت شده می‌توانند کتاب‌ها را مشاهده کنند (برای دیباگ)
        // TODO: مجدداً فعالسازی سیستم مجوز پس از تست
        // بررسی اینکه آیا کاربر می‌تواند این کتاب را مشاهده کند (مالک یا کتاب در دسترس باشد)
        // if ($book->owner_id !== Auth::id() && $book->status !== Book::STATUS_VERFUEGBAR) {
        //     abort(403, 'شما مجوز مشاهده این کتاب را ندارید. کتاب در حال حاضر در دسترس نیست.');
        // }

        // دریافت پیشنهادات برای این کتاب
        $recommendations = $this->categorizationService->getRecommendations($book);

        // دریافت آمار رتبه‌بندی
        $ratingStats = [
            'average' => round($book->average_rating, 1),
            'total' => $book->total_ratings,
            'user_rating' => $book->getUserRating(Auth::id()),
        ];

        // دریافت آخرین رتبه‌بندی‌ها
        $recentRatings = $book
            ->ratings()
            ->with('user')
            ->withReview()
            ->latest()
            ->limit(5)
            ->get();

        return view('books.show', compact('book', 'recommendations', 'ratingStats', 'recentRatings'));
    }

    /**
     * نمایش فرم ویرایش کتاب مشخص
     */
    public function edit(Book $book): View
    {
        // فقط مالک می‌تواند ویرایش کند
        if ($book->owner_id !== Auth::id()) {
            abort(403, 'Sie können nur Ihre eigenen Bücher bearbeiten.');
        }

        return view('books.edit', compact('book'));
    }

    /**
     * به‌روزرسانی کتاب مشخص در دیتابیس
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        // فقط مالک می‌تواند به‌روزرسانی کند
        if ($book->owner_id !== Auth::id()) {
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

        // آپلود تصویر جلد کتاب
        if ($request->hasFile('cover')) {
            $data['image_path'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('books.show', $book)->with('success', 'Buch wurde erfolgreich aktualisiert!');
    }

    /**
     * حذف کتاب مشخص از دیتابیس
     */
    public function destroy(Book $book): RedirectResponse
    {
        // فقط مالک می‌تواند حذف کند
        if ($book->owner_id !== Auth::id()) {
            abort(403, 'Sie können nur Ihre eigenen Bücher löschen.');
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buch wurde erfolgreich gelöscht!');
    }
}
