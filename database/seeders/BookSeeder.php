<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if none exists
        $user = User::firstOrCreate(
            ['email' => 'test@bookshare.de'],
            [
                'name' => 'Test Benutzer',
                'password' => bcrypt('password123'),
            ]
        );

        $books = [
            [
                'title' => 'Der Herr der Ringe: Die Gefährten',
                'author' => 'J.R.R. Tolkien',
                'isbn' => '9783608938041',
                'description' => 'Ein episches Fantasy-Abenteuer über die Reise zur Zerstörung des Einen Rings.',
                'genre' => 'Fantasy',
                'publication_year' => 1954,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $user->id,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '9783548234106',
                'description' => 'Ein dystopischer Roman über Überwachung und Kontrolle in einer totalitären Gesellschaft.',
                'genre' => 'Dystopie',
                'publication_year' => 1949,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'verfügbar',
                'owner_id' => $user->id,
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => '9780141439518',
                'description' => 'Ein klassischer Liebesroman über Elizabeth Bennet und Mr. Darcy.',
                'genre' => 'Romance',
                'publication_year' => 1813,
                'language' => 'englisch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $user->id,
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'description' => 'Ein Handbuch für agile Software-Entwicklung und sauberen Code.',
                'genre' => 'Sachbuch',
                'publication_year' => 2008,
                'language' => 'englisch',
                'condition' => 'sehr gut',
                'status' => 'verfügbar',
                'owner_id' => $user->id,
            ],
            [
                'title' => 'Die Verwandlung',
                'author' => 'Franz Kafka',
                'isbn' => '9783150090092',
                'description' => 'Eine surreale Erzählung über die Verwandlung eines Menschen in ein Ungeziefer.',
                'genre' => 'Klassiker',
                'publication_year' => 1915,
                'language' => 'deutsch',
                'condition' => 'befriedigend',
                'status' => 'verfügbar',
                'owner_id' => $user->id,
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}
