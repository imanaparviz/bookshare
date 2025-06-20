<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RatingController extends Controller
{
    /**
     * ذخیره یا به‌روزرسانی رتبه‌بندی کتاب
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $request->validate(Rating::validationRules());

        // Check if user already rated this book
        $existingRating = $book->getUserRating(Auth::id());

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'review' => $request->review,
                'is_anonymous' => $request->boolean('is_anonymous', false),
            ]);
            $message = 'Ihre Bewertung wurde erfolgreich aktualisiert!';
        } else {
            // Create new rating
            Rating::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'rating' => $request->rating,
                'review' => $request->review,
                'is_anonymous' => $request->boolean('is_anonymous', false),
            ]);
            $message = 'Vielen Dank für Ihre Bewertung!';
        }

        return back()->with('success', $message);
    }

    /**
     * حذف رتبه‌بندی
     */
    public function destroy(Book $book): RedirectResponse
    {
        $rating = $book->getUserRating(Auth::id());

        if (!$rating) {
            return back()->with('error', 'Sie haben dieses Buch noch nicht bewertet.');
        }

        $rating->delete();

        return back()->with('success', 'Ihre Bewertung wurde erfolgreich entfernt.');
    }

    /**
     * نمایش تمام رتبه‌بندی‌های یک کتاب
     */
    public function show(Book $book): View
    {
        $ratings = $book
            ->ratings()
            ->with('user')
            ->latest()
            ->paginate(10);

        $ratingStats = [
            'average' => round($book->average_rating, 1),
            'total' => $book->total_ratings,
            'distribution' => Rating::getRatingDistribution($book->id),
        ];

        return view('ratings.show', compact('book', 'ratings', 'ratingStats'));
    }

    /**
     * نمایش تمام رتبه‌بندی‌های کاربر
     */
    public function userRatings(): View
    {
        $ratings = Auth::user()
            ->ratings()
            ->with('book')
            ->latest()
            ->paginate(12);

        return view('ratings.user-ratings', compact('ratings'));
    }

    /**
     * دریافت آمار رتبه‌بندی برای API یا AJAX
     */
    public function getStats(Book $book)
    {
        return response()->json([
            'average_rating' => round($book->average_rating, 1),
            'total_ratings' => $book->total_ratings,
            'distribution' => Rating::getRatingDistribution($book->id),
            'user_rating' => $book->getUserRating(Auth::id()),
        ]);
    }
}
