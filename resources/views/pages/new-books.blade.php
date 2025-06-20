<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Neue Bücher - BookShare</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white">
    <!-- Navigation -->
    <nav class="bg-gray-800 p-6 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-2xl font-bold text-white">BookShare</span>
            </a>
            
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-indigo-300 transition-colors">Dashboard</a>
                    <a href="{{ route('books.index') }}" class="text-white hover:text-indigo-300 transition-colors">Meine Bücher</a>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-indigo-300 transition-colors">Anmelden</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-semibold transition-colors">Registrieren</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-br from-green-900 via-emerald-900 to-teal-900">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Neue Bücher</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Entdecke die neuesten Bücher, die unsere Community geteilt hat.
            </p>
        </div>
    </section>

    <!-- New Books -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-6xl mx-auto px-6">
            
            <!-- Filter Options -->
            <div class="flex flex-wrap gap-4 mb-8">
                <select class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>Alle Kategorien</option>
                    <option>Fantasy</option>
                    <option>Klassiker</option>
                    <option>Thriller</option>
                    <option>Sachbuch</option>
                </select>
                <select class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>Letzte 7 Tage</option>
                    <option>Letzte 30 Tage</option>
                    <option>Letzte 3 Monate</option>
                </select>
                <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-semibold transition-colors">
                    Filter anwenden
                </button>
            </div>

            <!-- Books Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                @php
                    $newBooks = [
                        ['title' => 'Der Herr der Ringe', 'author' => 'J.R.R. Tolkien', 'image' => '01_herr_der_ringe_die_gefaehrten.jpg', 'date' => 'Heute', 'category' => 'Fantasy'],
                        ['title' => 'Dune', 'author' => 'Frank Herbert', 'image' => '02_dune_der_wuestenplanet.jpg', 'date' => 'Gestern', 'category' => 'Science Fiction'],
                        ['title' => 'Harry Potter', 'author' => 'J.K. Rowling', 'image' => '03_harry_potter_stein_der_weisen.jpg', 'date' => 'Vor 2 Tagen', 'category' => 'Fantasy'],
                        ['title' => 'Foundation', 'author' => 'Isaac Asimov', 'image' => '04_foundation_der_psychohistoriker.jpg', 'date' => 'Vor 3 Tagen', 'category' => 'Science Fiction'],
                        ['title' => 'Game of Thrones', 'author' => 'George R.R. Martin', 'image' => '05_das_lied_von_eis_und_feuer.jpg', 'date' => 'Vor 4 Tagen', 'category' => 'Fantasy'],
                        ['title' => '1984', 'author' => 'George Orwell', 'image' => '06_1984_george_orwell.jpg', 'date' => 'Vor 5 Tagen', 'category' => 'Klassiker'],
                        ['title' => 'Stolz und Vorurteil', 'author' => 'Jane Austen', 'image' => '07_stolz_und_vorurteil.jpg', 'date' => 'Vor 6 Tagen', 'category' => 'Klassiker'],
                        ['title' => 'Die Verwandlung', 'author' => 'Franz Kafka', 'image' => '08_die_verwandlung_kafka.jpg', 'date' => 'Vor 1 Woche', 'category' => 'Klassiker'],
                    ];
                @endphp

                @foreach($newBooks as $book)
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all hover:scale-105">
                        <div class="relative">
                            @if(file_exists(public_path('images/' . $book['image'])))
                                <img src="{{ asset('images/' . $book['image']) }}" alt="{{ $book['title'] }}" class="w-full h-64 object-cover">
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <div class="text-4xl mb-2">📖</div>
                                        <div class="text-sm">{{ $book['title'] }}</div>
                                    </div>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-green-600 text-white text-xs rounded-full font-semibold">
                                    NEU
                                </span>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="px-2 py-1 bg-black/60 text-white text-xs rounded-full">
                                    {{ $book['date'] }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="mb-2">
                                <span class="inline-block px-2 py-1 bg-indigo-600 text-white text-xs rounded-full">
                                    {{ $book['category'] }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2">{{ $book['title'] }}</h3>
                            <p class="text-gray-400 text-sm mb-4">{{ $book['author'] }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full"></div>
                                    <span class="text-sm text-gray-400">Verfügbar</span>
                                </div>
                                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-sm font-semibold transition-colors">
                                    Details
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Load More -->
            <div class="text-center mt-12">
                <button class="px-8 py-3 bg-gray-800 hover:bg-gray-700 text-white rounded-lg font-semibold transition-colors">
                    Mehr laden
                </button>
            </div>

        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html> 