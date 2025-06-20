<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Rating;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Run existing seeders
        $this->call([
            BookSeeder::class,
        ]);

        // Create sample ratings for realistic AI recommendations
        $this->createSampleRatings();

        // Create sample loans for user history
        $this->createSampleLoans();
    }

    private function createSampleRatings()
    {
        $users = User::all();
        $books = Book::all();

        // Create realistic rating patterns
        $ratingPatterns = [
            // User 1: Liebt Fantasy und Sci-Fi
            1 => [
                'high_rated_genres' => ['Fantasy', 'Science Fiction'],
                'low_rated_genres' => ['Romance', 'Kochbuch'],
                'preferred_authors' => ['J.R.R. Tolkien', 'Frank Herbert', 'Isaac Asimov']
            ],
            // User 2: Liebt Klassiker und Literatur
            2 => [
                'high_rated_genres' => ['Klassiker', 'Biografie'],
                'low_rated_genres' => ['Horror', 'Thriller'],
                'preferred_authors' => ['Jane Austen', 'George Orwell', 'Walter Isaacson']
            ],
            // User 3: Liebt Thriller und Mystery
            3 => [
                'high_rated_genres' => ['Thriller', 'Mystery', 'Krimi'],
                'low_rated_genres' => ['Philosophie', 'Ratgeber'],
                'preferred_authors' => ['Gillian Flynn', 'Stieg Larsson', 'Agatha Christie']
            ],
            // User 4: Liebt Sachbücher und Ratgeber
            4 => [
                'high_rated_genres' => ['Sachbuch', 'Ratgeber', 'Biografie'],
                'low_rated_genres' => ['Fantasy', 'Horror'],
                'preferred_authors' => ['Yuval Noah Harari', 'James Clear', 'Robert C. Martin']
            ],
            // User 5: Liebt Humor und leichte Unterhaltung
            5 => [
                'high_rated_genres' => ['Humor', 'Jugendbuch'],
                'low_rated_genres' => ['Dystopie', 'Horror'],
                'preferred_authors' => ['Marc-Uwe Kling', 'Jonas Jonasson', 'Wolfgang Herrndorf']
            ]
        ];

        foreach ($users->take(5) as $user) {
            $pattern = $ratingPatterns[$user->id] ?? null;
            if (!$pattern)
                continue;

            // Rate 15-25 books per user
            $booksToRate = $books->random(rand(15, 25));

            foreach ($booksToRate as $book) {
                $rating = 3;  // default

                // Higher ratings for preferred genres
                if (in_array($book->genre, $pattern['high_rated_genres'])) {
                    $rating = rand(4, 5);
                }
                // Lower ratings for disliked genres
                elseif (in_array($book->genre, $pattern['low_rated_genres'])) {
                    $rating = rand(1, 3);
                }
                // Higher ratings for preferred authors
                elseif (in_array($book->author, $pattern['preferred_authors'])) {
                    $rating = 5;
                }
                // Random ratings for neutral books
                else {
                    $rating = rand(2, 4);
                }

                // Add some review text based on rating
                $reviewTexts = [
                    5 => ['Absolut fantastisch!', 'Ein Meisterwerk!', 'Kann ich nur empfehlen!', 'Perfekt geschrieben!'],
                    4 => ['Sehr gut!', 'Hat mir gefallen', 'Empfehlenswert', 'Solide Geschichte'],
                    3 => ['Okay', 'Durchschnittlich', 'Ganz nett', 'Mittelmäßig'],
                    2 => ['Nicht mein Geschmack', 'Eher langweilig', 'Hatte mehr erwartet'],
                    1 => ['Schlecht', 'Nicht empfehlenswert', 'Konnte mich nicht fesseln']
                ];

                Rating::firstOrCreate([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                ], [
                    'rating' => $rating,
                    'review' => $reviewTexts[$rating][array_rand($reviewTexts[$rating])] ?? null,
                    'created_at' => now()->subDays(rand(1, 180))
                ]);
            }
        }
    }

    private function createSampleLoans()
    {
        $users = User::all();
        $books = Book::all();

        foreach ($users->take(5) as $user) {
            // Create 5-10 loan history entries per user
            $booksToLoan = $books->where('owner_id', '!=', $user->id)->random(rand(5, 10));

            foreach ($booksToLoan as $book) {
                $startDate = now()->subDays(rand(30, 365));
                $endDate = $startDate->copy()->addDays(rand(7, 30));

                $statuses = ['zurückgegeben', 'zurückgegeben', 'zurückgegeben', 'aktiv'];  // mostly returned
                $status = $statuses[array_rand($statuses)];

                Loan::firstOrCreate([
                    'borrower_id' => $user->id,
                    'book_id' => $book->id,
                    'loan_date' => $startDate,
                ], [
                    'lender_id' => $book->owner_id,
                    'due_date' => $endDate,
                    'return_date' => $status === 'zurückgegeben' ? $endDate->copy()->subDays(rand(0, 5)) : null,
                    'status' => $status,
                    'created_at' => $startDate
                ]);
            }
        }
    }
}
