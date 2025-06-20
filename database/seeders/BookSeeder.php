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
        // Create multiple test users
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = User::firstOrCreate(
                ['email' => "user{$i}@bookshare.de"],
                [
                    'name' => "Benutzer {$i}",
                    'password' => bcrypt('password123'),
                ]
            );
        }

        $books = [
            // Fantasy & Science Fiction
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
                'owner_id' => $users[0]->id,
            ],
            [
                'title' => 'Dune - Der Wüstenplanet',
                'author' => 'Frank Herbert',
                'isbn' => '9783453317840',
                'description' => 'Ein episches Science-Fiction-Meisterwerk über Politik, Religion und Ökologie auf dem Wüstenplaneten Arrakis.',
                'genre' => 'Science Fiction',
                'publication_year' => 1965,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'verfügbar',
                'owner_id' => $users[1]->id,
            ],
            [
                'title' => 'Harry Potter und der Stein der Weisen',
                'author' => 'J.K. Rowling',
                'isbn' => '9783551551672',
                'description' => 'Der erste Band der magischen Harry Potter-Serie über den Jungen, der überlebte.',
                'genre' => 'Fantasy',
                'publication_year' => 1997,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $users[2]->id,
            ],
            [
                'title' => 'Foundation - Der Psychohistoriker',
                'author' => 'Isaac Asimov',
                'isbn' => '9783453528833',
                'description' => 'Ein Science-Fiction-Klassiker über die Vorhersage des Zerfalls eines galaktischen Imperiums.',
                'genre' => 'Science Fiction',
                'publication_year' => 1951,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $users[3]->id,
            ],
            [
                'title' => 'Das Lied von Eis und Feuer - Die Herren von Winterfell',
                'author' => 'George R.R. Martin',
                'isbn' => '9783442267743',
                'description' => 'Der Beginn der epischen Fantasy-Saga, die als Game of Thrones berühmt wurde.',
                'genre' => 'Fantasy',
                'publication_year' => 1996,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'angefragt',
                'owner_id' => $users[4]->id,
            ],
            // Klassiker & Literatur
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
                'owner_id' => $users[0]->id,
            ],
            [
                'title' => 'Stolz und Vorurteil',
                'author' => 'Jane Austen',
                'isbn' => '9783458177630',
                'description' => 'Ein klassischer Liebesroman über Elizabeth Bennet und Mr. Darcy im England der Regency-Zeit.',
                'genre' => 'Klassiker',
                'publication_year' => 1813,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $users[1]->id,
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
                'owner_id' => $users[2]->id,
            ],
            [
                'title' => 'Der große Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '9783596901968',
                'description' => 'Ein Klassiker der amerikanischen Literatur über Träume und Enttäuschungen in den 1920er Jahren.',
                'genre' => 'Klassiker',
                'publication_year' => 1925,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verliehen',
                'owner_id' => $users[3]->id,
            ],
            // Thriller & Krimi
            [
                'title' => 'Gone Girl - Das perfekte Opfer',
                'author' => 'Gillian Flynn',
                'isbn' => '9783596196265',
                'description' => 'Ein psychologischer Thriller über ein Ehepaar und das Verschwinden der Frau.',
                'genre' => 'Thriller',
                'publication_year' => 2012,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'verfügbar',
                'owner_id' => $users[4]->id,
            ],
            [
                'title' => 'Der Schwarm',
                'author' => 'Frank Schätzing',
                'isbn' => '9783462034745',
                'description' => 'Ein Öko-Thriller über eine Bedrohung aus den Tiefen der Ozeane.',
                'genre' => 'Thriller',
                'publication_year' => 2004,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $users[0]->id,
            ],
            [
                'title' => 'The Girl with the Dragon Tattoo',
                'author' => 'Stieg Larsson',
                'isbn' => '9783453435735',
                'description' => 'Ein schwedischer Krimi über Korruption, Gewalt und Rache.',
                'genre' => 'Krimi',
                'publication_year' => 2005,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $users[1]->id,
            ],
            // Sachbücher & Ratgeber
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
                'owner_id' => $users[2]->id,
            ],
            [
                'title' => 'Sapiens - Eine kurze Geschichte der Menschheit',
                'author' => 'Yuval Noah Harari',
                'isbn' => '9783570552698',
                'description' => 'Eine faszinierende Reise durch die Geschichte der Menschheit von der Steinzeit bis heute.',
                'genre' => 'Sachbuch',
                'publication_year' => 2011,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'angefragt',
                'owner_id' => $users[3]->id,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'isbn' => '9783442178582',
                'description' => 'Ein praktischer Leitfaden zur Entwicklung guter Gewohnheiten und zum Durchbrechen schlechter.',
                'genre' => 'Ratgeber',
                'publication_year' => 2018,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'verfügbar',
                'owner_id' => $users[4]->id,
            ],
            // Romane & Zeitgenössische Literatur
            [
                'title' => 'Die Känguru-Chroniken',
                'author' => 'Marc-Uwe Kling',
                'isbn' => '9783548372570',
                'description' => 'Humorvolle Geschichten über das Zusammenleben mit einem politisch korrekten Känguru.',
                'genre' => 'Humor',
                'publication_year' => 2009,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $users[0]->id,
            ],
            [
                'title' => 'Der Hundertjährige, der aus dem Fenster stieg',
                'author' => 'Jonas Jonasson',
                'isbn' => '9783899812251',
                'description' => 'Eine humorvolle Geschichte über Allan Karlsson und seine ungewöhnlichen Abenteuer.',
                'genre' => 'Humor',
                'publication_year' => 2009,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verfügbar',
                'owner_id' => $users[1]->id,
            ],
            [
                'title' => 'Tschick',
                'author' => 'Wolfgang Herrndorf',
                'isbn' => '9783499256356',
                'description' => 'Ein Jugendroman über eine Reise zweier Teenager durch die deutsche Provinz.',
                'genre' => 'Jugendbuch',
                'publication_year' => 2010,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'verfügbar',
                'owner_id' => $users[2]->id,
            ],
            // Biografien & Memoiren
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'isbn' => '9783570101926',
                'description' => 'Die autorisierte Biografie des Apple-Gründers und Visionärs.',
                'genre' => 'Biografie',
                'publication_year' => 2011,
                'language' => 'deutsch',
                'condition' => 'gut',
                'status' => 'verliehen',
                'owner_id' => $users[3]->id,
            ],
            [
                'title' => 'Becoming - Meine Geschichte',
                'author' => 'Michelle Obama',
                'isbn' => '9783442314256',
                'description' => 'Die Memoiren der ehemaligen First Lady der USA.',
                'genre' => 'Biografie',
                'publication_year' => 2018,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'verfügbar',
                'owner_id' => $users[4]->id,
            ],
            // Kochbücher & Lifestyle
            [
                'title' => 'Salt, Fat, Acid, Heat',
                'author' => 'Samin Nosrat',
                'isbn' => '9783442314263',
                'description' => 'Die vier Grundelemente guten Kochens erklärt von einer Meisterköchin.',
                'genre' => 'Kochbuch',
                'publication_year' => 2017,
                'language' => 'deutsch',
                'condition' => 'sehr gut',
                'status' => 'verfügbar',
                'owner_id' => $users[0]->id,
            ],
        ];

        foreach ($books as $bookData) {
            Book::firstOrCreate(
                ['isbn' => $bookData['isbn']],
                $bookData
            );
        }
    }
}
