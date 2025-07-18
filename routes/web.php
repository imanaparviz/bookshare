<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Test route for checking book images
Route::get('/test-images', function () {
    return view('test-images');
})->name('test-images');

// Debug route for rating system
Route::get('/debug-rating', function () {
    return view('debug-rating');
})->middleware('auth')->name('debug-rating');

// Public pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/blog', function () {
    return view('pages.blog');
})->name('blog');

Route::get('/help', function () {
    return view('pages.help');
})->name('help');

Route::get('/categories', function () {
    return view('pages.categories');
})->name('categories');

Route::get('/new-books', function () {
    return view('pages.new-books');
})->name('new-books');

Route::get('/popular-books', function () {
    return view('pages.popular-books');
})->name('popular-books');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/support', function () {
    return view('pages.support');
})->name('support');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/imprint', function () {
    return view('pages.imprint');
})->name('imprint');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Books routes
    Route::resource('books', BookController::class);

    // Loans routes
    Route::resource('loans', LoanController::class)->except(['create', 'edit']);

    // Messaging routes
    Route::prefix('conversations')->name('conversations.')->group(function () {
        Route::get('/', [App\Http\Controllers\ConversationController::class, 'index'])->name('index');
        Route::get('/{conversation}', [App\Http\Controllers\ConversationController::class, 'show'])->name('show');
        Route::post('/{conversation}/send', [App\Http\Controllers\ConversationController::class, 'sendMessage'])->name('send');
        Route::post('/{conversation}/quick', [App\Http\Controllers\ConversationController::class, 'sendQuickMessage'])->name('quick');
        Route::patch('/{conversation}/read', [App\Http\Controllers\ConversationController::class, 'markAllAsRead'])->name('read');
        Route::patch('/{conversation}/archive', [App\Http\Controllers\ConversationController::class, 'archive'])->name('archive');
        Route::get('/loan/{loan}', [App\Http\Controllers\ConversationController::class, 'showForLoan'])->name('loan');
        Route::post('/loan/{loan}/create', [App\Http\Controllers\ConversationController::class, 'createForLoan'])->name('create-for-loan');
    });

    // API routes for AJAX calls
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/unread-count', [App\Http\Controllers\ConversationController::class, 'getUnreadCount'])->name('unread-count');
    });

    // Ratings routes
    Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
    Route::get('/ratings/user/{user}', [RatingController::class, 'userRatings'])->name('ratings.user');
    Route::post('/books/{book}/rate', [RatingController::class, 'store'])->name('ratings.store');
    Route::delete('/books/{book}/ratings', [RatingController::class, 'destroy'])->name('ratings.destroy');
    Route::get('/books/{book}/ratings', [RatingController::class, 'show'])->name('ratings.show');

    // AI Recommendation routes
    Route::get('/recommendations', [WelcomeController::class, 'recommendations'])->name('recommendations');
    Route::get('/api/recommendations', [WelcomeController::class, 'getRecommendations'])->name('api.recommendations');
    Route::get('/api/ai-genre-recommendations/{genre}', [WelcomeController::class, 'getAIGenreRecommendations'])->name('api.ai-genre-recommendations');
    Route::get('/api/advanced-ai-recommendations', [WelcomeController::class, 'getAdvancedAIRecommendations'])->name('api.advanced-ai-recommendations');

    // Debug OpenAI Endpoint
    Route::get('/debug/openai-test', function () {
        if (!Auth::check()) {
            return 'Please login first';
        }

        $user = Auth::user();
        $service = new \App\Services\BookRecommendationService();

        try {
            $recommendations = $service->getAdvancedAIRecommendations($user, 3);

            return [
                'success' => true,
                'count' => $recommendations->count(),
                'recommendations' => $recommendations->toArray(),
                'message' => 'OpenAI integration test completed'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    });

    // Enhanced debug endpoint for full recommendation testing
    Route::get('/debug/full-ai-test', function () {
        Log::info('🤖 [DEBUG] === Full AI Recommendation Test ===');

        try {
            $user = \Illuminate\Support\Facades\Auth::user() ?: \App\Models\User::first();
            if (!$user) {
                return response()->json(['error' => 'No user found']);
            }

            Log::info('🤖 [DEBUG] Testing with user: ' . $user->name . ' (ID: ' . $user->id . ')');

            $recommendationService = new \App\Services\BookRecommendationService();
            $recommendations = $recommendationService->getAdvancedAIRecommendations($user, 3);

            Log::info('🤖 [DEBUG] Recommendations received: ' . $recommendations->count());

            return response()->json([
                'success' => true,
                'user' => $user->name,
                'recommendations_count' => $recommendations->count(),
                'recommendations' => $recommendations->toArray(),
                'message' => 'Full AI test completed!'
            ]);
        } catch (\Exception $e) {
            Log::error('🤖 [DEBUG] Full AI Test Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    });
});

require __DIR__ . '/auth.php';
