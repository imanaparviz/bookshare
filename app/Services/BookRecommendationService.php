<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class BookRecommendationService
{
    /**
     * Generiere personalisierte Buchempfehlungen basierend auf Bewertungen und Präferenzen
     */
    public function getPersonalizedRecommendations(User $user, int $limit = 5): Collection
    {
        // 1. Analysiere Benutzerpräferenzen basierend auf Bewertungen
        $userPreferences = $this->analyzeUserPreferences($user);

        // 2. Finde ähnliche Benutzer
        $similarUsers = $this->findSimilarUsers($user);

        // 3. Empfehlungen basierend auf ähnlichen Benutzern
        $collaborativeRecommendations = $this->getCollaborativeRecommendations($user, $similarUsers);

        // 4. Content-basierte Empfehlungen
        $contentBasedRecommendations = $this->getContentBasedRecommendations($user, $userPreferences);

        // 5. Kombiniere und gewichte Empfehlungen
        $finalRecommendations = $this->combineRecommendations(
            $collaborativeRecommendations,
            $contentBasedRecommendations
        );

        return $finalRecommendations->take($limit);
    }

    /**
     * Analysiere Benutzerpräferenzen basierend auf Bewertungen
     */
    public function analyzeUserPreferences(User $user): array
    {
        $userRatings = Rating::where('user_id', $user->id)
            ->with('book')
            ->where('rating', '>=', 4)  // Nur gut bewertete Bücher
            ->get();

        $preferences = [
            'genres' => [],
            'authors' => [],
            'avgRating' => 0,
            'totalRatings' => $userRatings->count(),
            'favoriteGenres' => [],
            'ratingPattern' => []
        ];

        if ($userRatings->isEmpty()) {
            return $preferences;
        }

        // Genre-Präferenzen
        $genreCounts = $userRatings->groupBy('book.genre')->map->count();
        $preferences['genres'] = $genreCounts->sortDesc()->take(3)->keys()->toArray();
        $preferences['favoriteGenres'] = $genreCounts->toArray();

        // Autoren-Präferenzen
        $authorCounts = $userRatings->groupBy('book.author')->map->count();
        $preferences['authors'] = $authorCounts->sortDesc()->take(3)->keys()->toArray();

        // Durchschnittliche Bewertung
        $preferences['avgRating'] = $userRatings->avg('rating');

        // Bewertungsmuster
        $ratingDistribution = $userRatings->groupBy('rating')->map->count();
        $preferences['ratingPattern'] = $ratingDistribution->toArray();

        return $preferences;
    }

    /**
     * Finde Benutzer mit ähnlichen Bewertungsmustern
     */
    private function findSimilarUsers(User $user): Collection
    {
        $userRatings = Rating::where('user_id', $user->id)->pluck('rating', 'book_id');

        if ($userRatings->isEmpty()) {
            return collect();
        }

        // Finde Benutzer, die mindestens 2 gemeinsame Bücher bewertet haben
        $similarUsers = DB::table('ratings as r1')
            ->join('ratings as r2', 'r1.book_id', '=', 'r2.book_id')
            ->where('r1.user_id', $user->id)
            ->where('r2.user_id', '!=', $user->id)
            ->select('r2.user_id', DB::raw('COUNT(*) as common_books'),
                DB::raw('AVG(ABS(r1.rating - r2.rating)) as rating_diff'))
            ->groupBy('r2.user_id')
            ->having('common_books', '>=', 2)
            ->orderBy('rating_diff', 'asc')
            ->orderBy('common_books', 'desc')
            ->limit(10)
            ->get();

        return $similarUsers;
    }

    /**
     * Collaborative Filtering Empfehlungen
     */
    private function getCollaborativeRecommendations(User $user, Collection $similarUsers): Collection
    {
        if ($similarUsers->isEmpty()) {
            return collect();
        }

        $userBookIds = Rating::where('user_id', $user->id)->pluck('book_id');
        $similarUserIds = $similarUsers->pluck('user_id');

        // Bücher, die ähnliche Benutzer gut bewertet haben, aber der aktuelle Benutzer noch nicht gelesen hat
        $recommendations = Rating::whereIn('user_id', $similarUserIds)
            ->whereNotIn('book_id', $userBookIds)
            ->where('rating', '>=', 4)
            ->with('book')
            ->select('book_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as rating_count'))
            ->groupBy('book_id')
            ->having('rating_count', '>=', 2)
            ->orderBy('avg_rating', 'desc')
            ->orderBy('rating_count', 'desc')
            ->limit(10)
            ->get();

        return $recommendations->map(function ($item) {
            return [
                'book' => Book::find($item->book_id),
                'score' => $item->avg_rating,
                'type' => 'collaborative',
                'reason' => 'Ähnliche Benutzer haben dieses Buch gut bewertet'
            ];
        })->filter(function ($item) {
            return $item['book'] && $item['book']->status === 'verfügbar';
        });
    }

    /**
     * Content-basierte Empfehlungen
     */
    private function getContentBasedRecommendations(User $user, array $preferences): Collection
    {
        if (empty($preferences['genres'])) {
            return collect();
        }

        $userBookIds = Rating::where('user_id', $user->id)->pluck('book_id');

        // Bücher aus bevorzugten Genres - Fix SQL Error
        $books = Book::whereNotIn('id', $userBookIds)
            ->whereIn('genre', $preferences['genres'])
            ->where('status', 'verfügbar')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->get();

        // Filter nur Bücher mit Bewertungen und sortiere
        $recommendations = $books->filter(function ($book) {
            return $book->ratings_count > 0;
        })->sortByDesc('ratings_avg_rating')->take(10);

        return $recommendations->map(function ($book) use ($preferences) {
            $score = $this->calculateContentScore($book, $preferences);
            return [
                'book' => $book,
                'score' => $score,
                'type' => 'content',
                'reason' => "Basierend auf Ihren Vorlieben für {$book->genre}"
            ];
        });
    }

    /**
     * Berechne Content-Score basierend auf Präferenzen
     */
    private function calculateContentScore(Book $book, array $preferences): float
    {
        $score = 0;

        // Genre-Übereinstimmung
        if (in_array($book->genre, $preferences['genres'])) {
            $genrePosition = array_search($book->genre, $preferences['genres']);
            $score += (3 - $genrePosition) * 2;  // Höhere Punkte für bevorzugte Genres
        }

        // Autor-Übereinstimmung
        if (in_array($book->author, $preferences['authors'])) {
            $score += 3;
        }

        // Bewertungsqualität
        if ($book->ratings_avg_rating) {
            $score += $book->ratings_avg_rating;
        }

        // Popularität (Anzahl Bewertungen)
        if ($book->ratings_count) {
            $score += min($book->ratings_count * 0.1, 2);  // Max 2 Punkte für Popularität
        }

        return $score;
    }

    /**
     * Kombiniere verschiedene Empfehlungstypen
     */
    private function combineRecommendations(Collection $collaborative, Collection $contentBased): Collection
    {
        $combined = collect();

        // Gewichte: 60% Collaborative, 40% Content-based
        $collaborative->each(function ($item) use ($combined) {
            $item['final_score'] = $item['score'] * 0.6;
            $combined->push($item);
        });

        $contentBased->each(function ($item) use ($combined) {
            $existing = $combined->firstWhere('book.id', $item['book']->id);
            if ($existing) {
                // Kombiniere Scores wenn Buch bereits existiert
                $existing['final_score'] += $item['score'] * 0.4;
                $existing['reason'] .= ' + ' . $item['reason'];
            } else {
                $item['final_score'] = $item['score'] * 0.4;
                $combined->push($item);
            }
        });

        return $combined->sortByDesc('final_score');
    }

    /**
     * Generiere AI-basierte Buchbeschreibung für bessere Empfehlungen
     */
    public function generateAIBookSummary(Book $book): string
    {
        try {
            $prompt = "Erstelle eine ansprechende, kurze Buchbeschreibung (max. 150 Wörter) für das folgende Buch:\n\n";
            $prompt .= "Titel: {$book->title}\n";
            $prompt .= "Autor: {$book->author}\n";
            $prompt .= "Genre: {$book->genre}\n";

            if ($book->description) {
                $prompt .= "Vorhandene Beschreibung: {$book->description}\n";
            }

            if ($book->ratings_avg_rating) {
                $prompt .= "Durchschnittliche Bewertung: {$book->ratings_avg_rating}/5 Sterne\n";
            }

            $prompt .= "\nErstelle eine überzeugende Beschreibung auf Deutsch, die Lust aufs Lesen macht und die wichtigsten Aspekte des Buches hervorhebt.";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Du bist ein Experte für Buchbeschreibungen und hilfst dabei, ansprechende Zusammenfassungen zu erstellen.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 200,
                'temperature' => 0.7,
            ]);

            return $response->choices[0]->message->content ?? $this->getFallbackSummary($book);
        } catch (\Exception $e) {
            // Fallback wenn OpenAI nicht verfügbar ist
            return $this->getFallbackSummary($book);
        }
    }

    /**
     * Fallback-Zusammenfassung wenn OpenAI nicht verfügbar ist
     */
    private function getFallbackSummary(Book $book): string
    {
        $summary = "📖 {$book->title} von {$book->author}";

        if ($book->genre) {
            $summary .= " ist ein {$book->genre}-Buch";
        }

        if ($book->ratings_avg_rating) {
            $rating = round($book->ratings_avg_rating, 1);
            $summary .= " mit einer durchschnittlichen Bewertung von {$rating}/5 Sternen";
        }

        if ($book->ratings_count) {
            $summary .= " ({$book->ratings_count} Bewertungen)";
        }

        if ($book->description) {
            $summary .= '. ' . Str::limit($book->description, 100);
        }

        return $summary;
    }

    /**
     * Analysiere Trends und beliebte Bücher
     */
    public function getTrendingBooks(int $limit = 5): Collection
    {
        $books = Book::withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('status', 'verfügbar')
            ->where('created_at', '>=', now()->subMonths(3))  // Nur neuere Bücher
            ->get();

        return $books
            ->filter(function ($book) {
                return $book->ratings_count >= 2;
            })
            ->sortByDesc('ratings_avg_rating')
            ->sortByDesc('ratings_count')
            ->take($limit);
    }

    /**
     * AI-gestützte personalisierte Empfehlungen basierend auf Benutzerprofil
     */
    public function getAIPersonalizedRecommendations(User $user, int $limit = 5): Collection
    {
        try {
            // Sammle Benutzerdaten für AI-Analyse
            $userRatings = Rating::where('user_id', $user->id)
                ->with('book')
                ->where('rating', '>=', 4)
                ->get();

            if ($userRatings->isEmpty()) {
                return collect();
            }

            // Erstelle Benutzerprofil für AI
            $likedBooks = $userRatings->map(function ($rating) {
                return [
                    'title' => $rating->book->title,
                    'author' => $rating->book->author,
                    'genre' => $rating->book->genre,
                    'rating' => $rating->rating,
                ];
            });

            $availableBooks = Book::where('status', 'verfügbar')
                ->whereNotIn('id', $userRatings->pluck('book_id'))
                ->get()
                ->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'author' => $book->author,
                        'genre' => $book->genre,
                        'description' => Str::limit($book->description ?? '', 100),
                    ];
                });

            $prompt = "Basierend auf den folgenden Büchern, die der Benutzer mit 4-5 Sternen bewertet hat:\n\n";
            foreach ($likedBooks as $book) {
                $prompt .= "- {$book['title']} von {$book['author']} ({$book['genre']}) - {$book['rating']}/5 Sterne\n";
            }

            $prompt .= "\nWähle die besten {$limit} Buchempfehlungen aus der folgenden Liste verfügbarer Bücher:\n\n";
            foreach ($availableBooks->take(20) as $book) {
                $prompt .= "ID: {$book['id']} - {$book['title']} von {$book['author']} ({$book['genre']})\n";
                if ($book['description']) {
                    $prompt .= "Beschreibung: {$book['description']}\n";
                }
                $prompt .= "\n";
            }

            $prompt .= "Gib nur die IDs der empfohlenen Bücher zurück, getrennt durch Kommas, zusammen mit einer kurzen Begründung für jede Empfehlung. Format: 'ID: Begründung'";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Du bist ein Experte für Buchempfehlungen. Analysiere die Vorlieben des Benutzers und wähle die besten passenden Bücher aus.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 300,
                'temperature' => 0.3,
            ]);

            $aiResponse = $response->choices[0]->message->content ?? '';

            return $this->parseAIRecommendations($aiResponse, $availableBooks);
        } catch (\Exception $e) {
            // Fallback zu normalen Empfehlungen
            return $this->getPersonalizedRecommendations($user, $limit);
        }
    }

    /**
     * Parse AI-Empfehlungen und erstelle strukturierte Ergebnisse
     */
    private function parseAIRecommendations(string $aiResponse, Collection $availableBooks): Collection
    {
        $recommendations = collect();
        $lines = explode("\n", $aiResponse);

        foreach ($lines as $line) {
            if (preg_match('/(\d+):\s*(.+)/', $line, $matches)) {
                $bookId = (int) $matches[1];
                $reason = trim($matches[2]);

                $book = Book::find($bookId);
                if ($book && $book->status === 'verfügbar') {
                    $recommendations->push([
                        'book' => $book,
                        'score' => 5.0,  // AI-Empfehlungen bekommen hohe Scores
                        'type' => 'ai',
                        'reason' => $reason
                    ]);
                }
            }
        }

        return $recommendations;
    }

    /**
     * Generiere AI-basierte Genre-Empfehlungen
     */
    public function getAIGenreRecommendations(string $genre, int $limit = 5): Collection
    {
        try {
            $books = Book::where('genre', $genre)
                ->where('status', 'verfügbar')
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->get();

            if ($books->isEmpty()) {
                return collect();
            }

            $bookList = $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'rating' => $book->ratings_avg_rating ?? 0,
                    'description' => Str::limit($book->description ?? '', 100),
                ];
            });

            $prompt = "Aus den folgenden {$genre}-Büchern, wähle die besten {$limit} Empfehlungen basierend auf Qualität, Popularität und Vielfalt:\n\n";

            foreach ($bookList as $book) {
                $prompt .= "ID: {$book['id']} - {$book['title']} von {$book['author']} (Bewertung: {$book['rating']}/5)\n";
                if ($book['description']) {
                    $prompt .= "Beschreibung: {$book['description']}\n";
                }
                $prompt .= "\n";
            }

            $prompt .= "Gib die IDs der empfohlenen Bücher zurück mit Begründung. Format: 'ID: Begründung'";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => "Du bist ein Experte für {$genre}-Literatur und hilfst bei der Auswahl der besten Bücher."],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 250,
                'temperature' => 0.4,
            ]);

            $aiResponse = $response->choices[0]->message->content ?? '';

            return $this->parseAIRecommendations($aiResponse, $bookList);
        } catch (\Exception $e) {
            // Fallback zu einfacher Genre-Filterung
            return Book::where('genre', $genre)
                ->where('status', 'verfügbar')
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->get()
                ->filter(function ($book) {
                    return $book->ratings_count > 0;
                })
                ->sortByDesc('ratings_avg_rating')
                ->take($limit)
                ->map(function ($book) {
                    return [
                        'book' => $book,
                        'score' => $book->ratings_avg_rating ?? 0,
                        'type' => 'genre',
                        'reason' => "Beliebtes {$book->genre}-Buch"
                    ];
                });
        }
    }

    /**
     * 🤖 SPEZIELLE OPENAI-FUNKTION: Analysiert alle Interaktionen des Benutzers
     * und gibt intelligente Empfehlungen basierend auf dem kompletten Verhalten
     */
    public function getAdvancedAIRecommendations(User $user, int $limit = 6): Collection
    {
        Log::info('🤖 [OpenAI] === SERVICE: getAdvancedAIRecommendations Started ===');
        Log::info('🤖 [OpenAI] User: ' . $user->name . ' (ID: ' . $user->id . ')');
        Log::info('🤖 [OpenAI] Requested limit: ' . $limit);

        try {
            Log::info('🤖 [OpenAI] --- Collecting User Data ---');

            // Sammle ALLE Benutzerdaten für umfassende AI-Analyse
            $userRatings = Rating::where('user_id', $user->id)->with('book')->get();
            $userBooks = Book::where('owner_id', $user->id)->get();
            $userLoans = \App\Models\Loan::where('borrower_id', $user->id)->with('book')->get();

            Log::info('🤖 [OpenAI] User ratings count: ' . $userRatings->count());
            Log::info('🤖 [OpenAI] User owned books count: ' . $userBooks->count());
            Log::info('🤖 [OpenAI] User loans count: ' . $userLoans->count());

            // Benutzer-Aktivitätsprofil erstellen
            $userProfile = [
                'rated_books' => $userRatings->map(function ($rating) {
                    return [
                        'title' => $rating->book->title,
                        'author' => $rating->book->author,
                        'genre' => $rating->book->genre,
                        'rating' => $rating->rating,
                        'description' => Str::limit($rating->book->description ?? '', 80)
                    ];
                }),
                'owned_books' => $userBooks->map(function ($book) {
                    return [
                        'title' => $book->title,
                        'author' => $book->author,
                        'genre' => $book->genre,
                        'description' => Str::limit($book->description ?? '', 80)
                    ];
                }),
                'borrowed_books' => $userLoans->map(function ($loan) {
                    return [
                        'title' => $loan->book->title,
                        'author' => $loan->book->author,
                        'genre' => $loan->book->genre,
                        'status' => $loan->status
                    ];
                })
            ];

            // Verfügbare Bücher für Empfehlungen
            $excludeIds = $userRatings
                ->pluck('book_id')
                ->merge($userBooks->pluck('id'))
                ->merge($userLoans->pluck('book_id'))
                ->unique();

            $availableBooks = Book::where('status', 'verfügbar')
                ->whereNotIn('id', $excludeIds)
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->get()
                ->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'author' => $book->author,
                        'genre' => $book->genre,
                        'avg_rating' => round($book->ratings_avg_rating ?? 0, 1),
                        'rating_count' => $book->ratings_count ?? 0,
                        'description' => Str::limit($book->description ?? '', 100),
                    ];
                });

            Log::info('🤖 [OpenAI] --- Preparing User Profile ---');
            Log::info('🤖 [OpenAI] Available books for recommendations: ' . $availableBooks->count());
            Log::info('🤖 [OpenAI] Excluded book IDs count: ' . $excludeIds->count());

            // Check if we have a real API key or use mock
            $apiKey = config('openai.api_key');
            $isMockMode = !$apiKey || $apiKey === 'sk-your-api-key-here' || str_starts_with($apiKey, 'sk-your-api-key');

            Log::info('🤖 [OpenAI] API Key status: ' . ($isMockMode ? 'MOCK MODE' : 'REAL API'));

            if ($isMockMode) {
                Log::info('🤖 [OpenAI] --- USING MOCK OPENAI RESPONSE ---');

                // Generate mock AI response based on user preferences
                $mockResponse = $this->generateMockAIResponse($userProfile, $availableBooks, $limit);
                $aiResponse = $mockResponse;

                Log::info('🤖 [OpenAI] Mock AI Response: ' . $aiResponse);
            } else {
                // Real OpenAI API call
                $prompt = $this->buildOpenAIPrompt($userProfile, $availableBooks, $limit);

                Log::info('🤖 [OpenAI] --- SENDING REQUEST TO OPENAI ---');
                Log::info('🤖 [OpenAI] Prompt length: ' . strlen($prompt) . ' characters');

                $openaiStartTime = microtime(true);

                $response = OpenAI::chat()->create([
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Du bist ein hochintelligenter Buchempfehlungs-AI mit Expertenwissen über Literatur aller Genres. Du analysierst Lesegewohnheiten präzise und gibst perfekt passende, vielfältige Empfehlungen.'
                        ],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 400,
                    'temperature' => 0.4,
                ]);

                $openaiExecutionTime = round((microtime(true) - $openaiStartTime) * 1000, 2);
                Log::info('🤖 [OpenAI] OpenAI API call completed in: ' . $openaiExecutionTime . ' ms');

                $aiResponse = $response->choices[0]->message->content ?? '';
                Log::info('🤖 [OpenAI] AI Response content: ' . $aiResponse);
            }

            Log::info('🤖 [OpenAI] --- PARSING AI RESPONSE ---');

            // Spezielle AI-Parsing für erweiterte Empfehlungen
            $parsedRecommendations = $this->parseAdvancedAIRecommendations($aiResponse, $availableBooks);

            Log::info('🤖 [OpenAI] Parsed recommendations count: ' . $parsedRecommendations->count());
            Log::info('🤖 [OpenAI] === SERVICE: Successfully returning recommendations ===');

            return $parsedRecommendations;
        } catch (\Exception $e) {
            Log::error('🤖 [OpenAI] === SERVICE ERROR ===');
            Log::error('🤖 [OpenAI] Exception type: ' . get_class($e));
            Log::error('🤖 [OpenAI] Error message: ' . $e->getMessage());
            Log::error('🤖 [OpenAI] Error file: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('🤖 [OpenAI] Stack trace: ' . $e->getTraceAsString());
            Log::info('🤖 [OpenAI] Falling back to standard AI recommendations');

            // Fallback zu normalen AI-Empfehlungen
            return $this->getAIPersonalizedRecommendations($user, $limit);
        }
    }

    /**
     * Generate mock AI response for testing
     */
    private function generateMockAIResponse(array $userProfile, Collection $availableBooks, int $limit): string
    {
        if ($availableBooks->isEmpty()) {
            return 'Keine verfügbaren Bücher für Empfehlungen gefunden.';
        }

        // Analyze user preferences from ratings
        $preferredGenres = [];
        if (!$userProfile['rated_books']->isEmpty()) {
            $genreRatings = [];
            foreach ($userProfile['rated_books'] as $ratedBook) {
                $genre = $ratedBook['genre'];
                if (!isset($genreRatings[$genre])) {
                    $genreRatings[$genre] = [];
                }
                $genreRatings[$genre][] = $ratedBook['rating'];
            }

            // Calculate average rating per genre
            foreach ($genreRatings as $genre => $ratings) {
                $avgRating = array_sum($ratings) / count($ratings);
                if ($avgRating >= 4.0) {
                    $preferredGenres[] = $genre;
                }
            }
        }

        // Generate recommendations based on preferences
        $recommendations = [];
        $recommendedBooks = $availableBooks->shuffle();

        // Prefer books from liked genres
        if (!empty($preferredGenres)) {
            $genreBooks = $recommendedBooks->filter(function ($book) use ($preferredGenres) {
                return in_array($book['genre'], $preferredGenres);
            });
            $recommendations = array_merge($recommendations, $genreBooks->take($limit - 1)->toArray());
        }

        // Fill remaining slots with other good books
        $remaining = $limit - count($recommendations);
        if ($remaining > 0) {
            $otherBooks = $recommendedBooks->filter(function ($book) use ($recommendations) {
                return !in_array($book['id'], array_column($recommendations, 'id'));
            });
            $recommendations = array_merge($recommendations, $otherBooks->take($remaining)->toArray());
        }

        // Format as AI response
        $response = '';
        foreach (array_slice($recommendations, 0, $limit) as $index => $book) {
            $reason = $this->generateMockReason($book, $preferredGenres, $userProfile);
            $response .= "{$book['id']}: {$reason}\n";
        }

        return trim($response);
    }

    /**
     * Generate mock reasoning for book recommendations
     */
    private function generateMockReason(array $book, array $preferredGenres, array $userProfile): string
    {
        $reasons = [];

        // Genre-based reasoning
        if (in_array($book['genre'], $preferredGenres)) {
            $reasons[] = "Perfekt für {$book['genre']}-Liebhaber";
        }

        // Rating-based reasoning
        if ($book['avg_rating'] >= 4.5) {
            $reasons[] = "Hochbewertet ({$book['avg_rating']}/5)";
        } elseif ($book['avg_rating'] >= 4.0) {
            $reasons[] = 'Sehr gut bewertet';
        }

        // Author-based reasoning
        $readAuthors = $userProfile['rated_books']->pluck('author')->unique()->toArray();
        if (in_array($book['author'], $readAuthors)) {
            $reasons[] = "Sie kennen bereits {$book['author']}";
        }

        // Default reasons by genre
        $genreReasons = [
            'Fantasy' => 'Epische Abenteuer und Magie erwarten Sie',
            'Science Fiction' => 'Faszinierende Zukunftsvisionen',
            'Thriller' => 'Spannung pur bis zur letzten Seite',
            'Klassiker' => 'Zeitlose Literatur von bleibendem Wert',
            'Biografie' => 'Inspirierendes Leben einer bemerkenswerten Person',
            'Sachbuch' => 'Wertvolles Wissen für den Alltag',
            'Humor' => 'Garantiert für gute Laune',
            'Mystery' => 'Rätselhaft und fesselnd'
        ];

        if (empty($reasons)) {
            $reasons[] = $genreReasons[$book['genre']] ?? 'Empfehlung basierend auf Ihrem Profil';
        }

        return implode(', ', $reasons);
    }

    /**
     * Build OpenAI prompt for real API calls
     */
    private function buildOpenAIPrompt(array $userProfile, Collection $availableBooks, int $limit): string
    {
        $prompt = "🤖 BUCHEMPFEHLUNGS-EXPERTE: Analysiere das folgende Benutzerprofil und gib die besten {$limit} Buchempfehlungen:\n\n";

        $prompt .= "=== BENUTZERPROFIL ===\n";

        if ($userProfile['rated_books']->isNotEmpty()) {
            $prompt .= "BEWERTETE BÜCHER:\n";
            foreach ($userProfile['rated_books'] as $book) {
                $prompt .= "• {$book['title']} von {$book['author']} ({$book['genre']}) - {$book['rating']}/5 ⭐\n";
            }
            $prompt .= "\n";
        }

        if ($userProfile['owned_books']->isNotEmpty()) {
            $prompt .= "EIGENE BÜCHER:\n";
            foreach ($userProfile['owned_books'] as $book) {
                $prompt .= "• {$book['title']} von {$book['author']} ({$book['genre']})\n";
            }
            $prompt .= "\n";
        }

        if ($userProfile['borrowed_books']->isNotEmpty()) {
            $prompt .= "AUSGELIEHENE BÜCHER:\n";
            foreach ($userProfile['borrowed_books'] as $book) {
                $prompt .= "• {$book['title']} von {$book['author']} ({$book['genre']})\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "=== VERFÜGBARE BÜCHER ===\n";
        foreach ($availableBooks->take(25) as $book) {
            $prompt .= "ID: {$book['id']} | {$book['title']} von {$book['author']} ({$book['genre']})";
            if ($book['avg_rating'] > 0) {
                $prompt .= " | ⭐ {$book['avg_rating']}/5 ({$book['rating_count']} Bewertungen)";
            }
            $prompt .= "\n";
            if ($book['description']) {
                $prompt .= "   → {$book['description']}\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "=== AUFGABE ===\n";
        $prompt .= "Basierend auf diesem umfassenden Profil, wähle die {$limit} besten Buchempfehlungen aus.\n";
        $prompt .= "Berücksichtige: Genre-Präferenzen, Bewertungsmuster, Autoren-Vorlieben, Qualität der verfügbaren Bücher.\n";
        $prompt .= "Gib eine vielfältige Auswahl, die den Lesegeschmack erweitert.\n\n";
        $prompt .= "WICHTIGES ANTWORT-FORMAT (GENAU SO):\n";
        $prompt .= "ID: Begründung\n";
        $prompt .= "ID: Begründung\n";
        $prompt .= "ID: Begründung\n\n";
        $prompt .= "BEISPIELE:\n";
        $prompt .= "25: Fantasy-Fan? Der Hobbit passt perfekt zu Tolkien-Liebhabern\n";
        $prompt .= "43: Stephen King Horror für Thriller-Enthusiasten\n";
        $prompt .= "14: Harari-Fans werden Sapiens lieben\n\n";
        $prompt .= "WICHTIG: Verwende nur die verfügbaren Buch-IDs von oben! Format: 'ID: Grund' pro Zeile.";

        return $prompt;
    }

    /**
     * Parse erweiterte AI-Empfehlungen - ROBUST VERSION
     */
    private function parseAdvancedAIRecommendations(string $aiResponse, Collection $availableBooks): Collection
    {
        Log::info('🤖 [OpenAI] === PARSING AI RESPONSE ===');
        Log::info('🤖 [OpenAI] Response to parse: ' . $aiResponse);
        Log::info('🤖 [OpenAI] Available books for matching: ' . $availableBooks->count());

        $recommendations = collect();
        $lines = explode("\n", $aiResponse);

        Log::info('🤖 [OpenAI] Split response into ' . count($lines) . ' lines');

        foreach ($lines as $lineIndex => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            Log::info('🤖 [OpenAI] Processing line ' . ($lineIndex + 1) . ': ' . $line);

            // MEHRERE PARSING-PATTERNS für maximale Kompatibilität
            $bookId = null;
            $reason = '';

            // Pattern 1: "ID: reason" (Standard)
            if (preg_match('/^(?:ID\s*)?(\d+):\s*(.+)$/i', $line, $matches)) {
                $bookId = (int) $matches[1];
                $reason = trim($matches[2]);
                Log::info('🤖 [OpenAI] Pattern 1 match - ID: ' . $bookId . ', Reason: ' . $reason);
            }
            // Pattern 2: "- ID: reason" (mit Bullet Point)
            elseif (preg_match('/^[-•*]\s*(?:ID\s*)?(\d+):\s*(.+)$/i', $line, $matches)) {
                $bookId = (int) $matches[1];
                $reason = trim($matches[2]);
                Log::info('🤖 [OpenAI] Pattern 2 match - ID: ' . $bookId . ', Reason: ' . $reason);
            }
            // Pattern 3: "Book ID number" irgendwo in der Zeile
            elseif (preg_match('/(?:book|buch|id)\s*(?:id|nr|nummer)?\s*(\d+)/i', $line, $matches)) {
                $bookId = (int) $matches[1];
                $reason = preg_replace('/(?:book|buch|id)\s*(?:id|nr|nummer)?\s*\d+:?\s*/i', '', $line);
                $reason = trim($reason);
                Log::info('🤖 [OpenAI] Pattern 3 match - ID: ' . $bookId . ', Reason: ' . $reason);
            }
            // Pattern 4: Nur eine Nummer am Anfang
            elseif (preg_match('/^(\d+)\.?\s*(.+)$/', $line, $matches)) {
                $bookId = (int) $matches[1];
                $reason = trim($matches[2]);
                Log::info('🤖 [OpenAI] Pattern 4 match - ID: ' . $bookId . ', Reason: ' . $reason);
            }

            if ($bookId) {
                Log::info('🤖 [OpenAI] Searching for book ID: ' . $bookId);

                $book = Book::find($bookId);
                if ($book) {
                    Log::info('🤖 [OpenAI] Book found: ' . $book->title . ' (Status: ' . $book->status . ')');

                    if ($book->status === 'verfügbar') {
                        $recommendations->push([
                            'book' => $book,
                            'score' => 9.5 - ($recommendations->count() * 0.1),  // Leicht absteigende Scores
                            'type' => 'advanced-ai',
                            'reason' => $reason ?: 'KI-Empfehlung basierend auf Ihrem Profil',
                            'ai_confidence' => 'Hoch',
                            'recommendation_source' => '🤖 GPT-4 Vollanalyse'
                        ]);
                        Log::info('🤖 [OpenAI] ✅ Added recommendation: ' . $book->title);
                    } else {
                        Log::warning('🤖 [OpenAI] ❌ Book not available: ' . $book->title . ' (Status: ' . $book->status . ')');
                    }
                } else {
                    Log::warning('🤖 [OpenAI] ❌ Book ID ' . $bookId . ' not found in database');
                }
            } else {
                Log::warning('🤖 [OpenAI] ❌ No book ID found in line: ' . $line);
            }
        }

        Log::info('🤖 [OpenAI] === INITIAL PARSING COMPLETED ===');
        Log::info('🤖 [OpenAI] Recommendations found: ' . $recommendations->count());

        // FALLBACK: Wenn keine Empfehlungen gefunden wurden, erstelle welche basierend auf Genres
        if ($recommendations->isEmpty()) {
            Log::warning('🤖 [OpenAI] No recommendations parsed, creating fallback recommendations');

            // Nimm einfach die ersten verfügbaren Bücher aus verschiedenen Genres
            $fallbackBooks = Book::where('status', 'verfügbar')
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->orderBy('ratings_avg_rating', 'desc')
                ->take(6)
                ->get();

            foreach ($fallbackBooks as $index => $book) {
                $recommendations->push([
                    'book' => $book,
                    'score' => 8.0 - ($index * 0.2),
                    'type' => 'fallback-ai',
                    'reason' => 'Hochbewertetes Buch aus dem Genre ' . $book->genre,
                    'ai_confidence' => 'Mittel',
                    'recommendation_source' => '🤖 Fallback-Empfehlung'
                ]);
            }

            Log::info('🤖 [OpenAI] Created ' . $fallbackBooks->count() . ' fallback recommendations');
        }

        $finalRecommendations = $recommendations->take(6);
        Log::info('🤖 [OpenAI] === PARSING COMPLETED ===');
        Log::info('🤖 [OpenAI] Final recommendations count: ' . $finalRecommendations->count());

        return $finalRecommendations;
    }
}
