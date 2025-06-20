<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    protected $recommendationService;

    public function __construct(BookRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Zeige die Welcome-Seite mit AI-Empfehlungen
     */
    public function index(): View
    {
        $data = [];

        // Wenn Benutzer eingeloggt ist, generiere personalisierte Empfehlungen
        if (Auth::check()) {
            $data['personalizedRecommendations'] = $this
                ->recommendationService
                ->getPersonalizedRecommendations(Auth::user(), 6);

            // AI-powered recommendations
            $data['aiRecommendations'] = $this
                ->recommendationService
                ->getAIPersonalizedRecommendations(Auth::user(), 5);

            $data['userPreferences'] = $this
                ->recommendationService
                ->analyzeUserPreferences(Auth::user());
        }

        // Trending Books für alle Benutzer
        $data['trendingBooks'] = $this->recommendationService->getTrendingBooks(8);

        return view('welcome', $data);
    }

    /**
     * API Endpoint für AJAX-Empfehlungen
     */
    public function getRecommendations(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $limit = $request->get('limit', 5);
        $recommendations = $this
            ->recommendationService
            ->getPersonalizedRecommendations(Auth::user(), $limit);

        return response()->json([
            'recommendations' => $recommendations->map(function ($item) {
                return [
                    'book' => [
                        'id' => $item['book']->id,
                        'title' => $item['book']->title,
                        'author' => $item['book']->author,
                        'genre' => $item['book']->genre,
                        'image_path' => $item['book']->image_path,
                        'ratings_avg_rating' => $item['book']->ratings_avg_rating,
                        'ratings_count' => $item['book']->ratings_count,
                    ],
                    'score' => round($item['final_score'], 2),
                    'reason' => $item['reason'],
                    'type' => $item['type'],
                    'ai_summary' => $this->recommendationService->generateAIBookSummary($item['book'])
                ];
            })
        ]);
    }

    /**
     * Zeige detaillierte Empfehlungsseite
     */
    public function recommendations()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $recommendations = $this->recommendationService->getPersonalizedRecommendations($user, 10);
        $userPreferences = $this->recommendationService->analyzeUserPreferences($user);
        $trendingBooks = $this->recommendationService->getTrendingBooks(5);

        return view('recommendations', compact('recommendations', 'userPreferences', 'trendingBooks'));
    }

    /**
     * API Endpoint für AI-Genre-Empfehlungen
     */
    public function getAIGenreRecommendations($genre)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $recommendations = $this->recommendationService->getAIGenreRecommendations($genre, 5);

        return response()->json([
            'genre' => $genre,
            'recommendations' => $recommendations->map(function ($item) {
                return [
                    'book' => [
                        'id' => $item['book']->id,
                        'title' => $item['book']->title,
                        'author' => $item['book']->author,
                        'genre' => $item['book']->genre,
                        'image_path' => $item['book']->image_path,
                        'ratings_avg_rating' => $item['book']->ratings_avg_rating,
                        'ratings_count' => $item['book']->ratings_count,
                    ],
                    'score' => round($item['score'], 2),
                    'reason' => $item['reason'],
                    'type' => $item['type'],
                    'ai_summary' => $this->recommendationService->generateAIBookSummary($item['book'])
                ];
            })
        ]);
    }

    /**
     * 🤖 SPEZIELLE OPENAI-FUNKTION: Erweiterte AI-Analyse für Buchempfehlungen
     * Analysiert ALLE Benutzeraktivitäten und gibt intelligente Empfehlungen
     */
    public function getAdvancedAIRecommendations()
    {
        Log::info('🤖 [OpenAI] === CONTROLLER: Advanced AI Recommendations Started ===');
        Log::info('🤖 [OpenAI] Timestamp: ' . now()->toDateTimeString());
        Log::info('🤖 [OpenAI] Request IP: ' . request()->ip());
        Log::info('🤖 [OpenAI] User Agent: ' . request()->header('User-Agent'));

        if (!Auth::check()) {
            Log::warning('🤖 [OpenAI] Authentication failed - user not logged in');
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $user = Auth::user();
        Log::info('🤖 [OpenAI] User authenticated: ' . $user->email . ' (ID: ' . $user->id . ')');
        Log::info('🤖 [OpenAI] User name: ' . $user->name);

        try {
            Log::info('🤖 [OpenAI] --- Calling BookRecommendationService ---');
            $startTime = microtime(true);

            $recommendations = $this->recommendationService->getAdvancedAIRecommendations($user, 6);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info('🤖 [OpenAI] Service call completed in: ' . $executionTime . ' ms');
            Log::info('🤖 [OpenAI] Recommendations count: ' . $recommendations->count());

            if ($recommendations->isEmpty()) {
                Log::warning('🤖 [OpenAI] No recommendations returned from service');
            } else {
                Log::info('🤖 [OpenAI] Recommendations details:');
                $recommendations->each(function ($item, $index) {
                    Log::info('🤖 [OpenAI] Recommendation ' . ($index + 1) . ': ' . $item['book']->title . ' by ' . $item['book']->author . ' (Score: ' . $item['score'] . ')');
                });
            }

            Log::info('🤖 [OpenAI] --- Preparing JSON response ---');
            $response = [
                'success' => true,
                'total_found' => $recommendations->count(),
                'analysis_type' => '🤖 GPT-4 Vollanalyse',
                'recommendations' => $recommendations->map(function ($item) {
                    return [
                        'book' => [
                            'id' => $item['book']->id,
                            'title' => $item['book']->title,
                            'author' => $item['book']->author,
                            'genre' => $item['book']->genre,
                            'image_path' => $item['book']->image_path,
                            'ratings_avg_rating' => $item['book']->ratings_avg_rating,
                            'ratings_count' => $item['book']->ratings_count,
                            'status' => $item['book']->status,
                        ],
                        'score' => $item['score'],
                        'reason' => $item['reason'],
                        'type' => $item['type'],
                        'ai_confidence' => $item['ai_confidence'] ?? 'Hoch',
                        'recommendation_source' => $item['recommendation_source'] ?? '🤖 OpenAI',
                        'ai_summary' => $this->recommendationService->generateAIBookSummary($item['book'])
                    ];
                })
            ];

            Log::info('🤖 [OpenAI] Response prepared with ' . count($response['recommendations']) . ' recommendations');
            Log::info('🤖 [OpenAI] === CONTROLLER: Successfully returning response ===');

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('🤖 [OpenAI] === CONTROLLER ERROR ===');
            Log::error('🤖 [OpenAI] Error type: ' . get_class($e));
            Log::error('🤖 [OpenAI] Error message: ' . $e->getMessage());
            Log::error('🤖 [OpenAI] Error file: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('🤖 [OpenAI] Error trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
                'message' => 'AI analysis failed: ' . $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }
}
