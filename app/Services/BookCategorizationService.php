<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookCategorizationService
{
    private array $categories = [
        'belletristik' => ['roman', 'fiction', 'literature', 'story', 'novel'],
        'sachbuch' => ['non-fiction', 'manual', 'guide', 'handbook', 'biography'],
        'wissenschaft' => ['science', 'research', 'academic', 'study', 'theory'],
        'technik' => ['programming', 'computer', 'technology', 'software', 'development'],
        'geschichte' => ['history', 'historical', 'past', 'ancient', 'war'],
        'biografie' => ['biography', 'memoir', 'life', 'autobiography'],
        'kinder' => ['children', 'kids', 'youth', 'young', 'fairy'],
        'fantasy' => ['fantasy', 'magic', 'dragon', 'wizard', 'mythical'],
        'krimi' => ['crime', 'mystery', 'detective', 'thriller', 'murder'],
        'ratgeber' => ['self-help', 'advice', 'tips', 'how-to', 'guide'],
    ];

    /**
     * Categorize a book based on its title, description, and genre
     */
    public function categorizeBook(Book $book): string
    {
        $text = $this->prepareText($book);

        // Try AI categorization first (if available)
        $aiCategory = $this->tryAICategorization($text);
        if ($aiCategory) {
            return $aiCategory;
        }

        // Fall back to keyword-based categorization
        return $this->keywordBasedCategorization($text);
    }

    /**
     * Prepare text for analysis
     */
    private function prepareText(Book $book): string
    {
        $text = strtolower($book->title ?? '');

        if ($book->description) {
            $text .= ' ' . strtolower($book->description);
        }

        if ($book->genre) {
            $text .= ' ' . strtolower($book->genre);
        }

        return $text;
    }

    /**
     * Try AI-based categorization using external API
     */
    private function tryAICategorization(string $text): ?string
    {
        try {
            // This is a mock implementation
            // In real scenario, you would call OpenAI API or similar service

            // For demo purposes, we'll simulate an AI response
            if (env('AI_CATEGORIZATION_ENABLED', false)) {
                $response = Http::timeout(5)->post(env('AI_CATEGORIZATION_URL'), [
                    'text' => $text,
                    'language' => 'de',
                    'categories' => array_keys($this->categories)
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    return $result['category'] ?? null;
                }
            }
        } catch (\Exception $e) {
            Log::warning('AI categorization failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Keyword-based categorization as fallback
     */
    private function keywordBasedCategorization(string $text): string
    {
        $scores = [];

        foreach ($this->categories as $category => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                $score += substr_count($text, $keyword);
            }
            $scores[$category] = $score;
        }

        // Return category with highest score, or 'allgemein' if no matches
        $maxCategory = array_keys($scores, max($scores))[0];
        return max($scores) > 0 ? $maxCategory : 'allgemein';
    }

    /**
     * Get book recommendations based on similar categories and popularity
     */
    public function getRecommendations(Book $book, int $limit = 5): array
    {
        $category = $book->genre ?? $this->categorizeBook($book);

        // Get books in same category, excluding the current book
        $recommendations = Book::where('genre', $category)
            ->where('id', '!=', $book->id)
            ->where('status', 'verfügbar')
            ->withCount('loans')  // Add loan count for popularity
            ->orderBy('loans_count', 'desc')  // Order by popularity
            ->limit($limit)
            ->get();

        // If not enough recommendations in same category, get popular books
        if ($recommendations->count() < $limit) {
            $additional = Book::where('id', '!=', $book->id)
                ->where('status', 'verfügbar')
                ->whereNotIn('id', $recommendations->pluck('id'))
                ->withCount('loans')
                ->orderBy('loans_count', 'desc')
                ->limit($limit - $recommendations->count())
                ->get();

            $recommendations = $recommendations->merge($additional);
        }

        return $recommendations->toArray();
    }

    /**
     * Update book category automatically
     */
    public function updateBookCategory(Book $book): void
    {
        $newCategory = $this->categorizeBook($book);

        if (!$book->genre || $book->genre === 'allgemein') {
            $book->update(['genre' => $newCategory]);
            Log::info("Book '{$book->title}' categorized as: {$newCategory}");
        }
    }
}
