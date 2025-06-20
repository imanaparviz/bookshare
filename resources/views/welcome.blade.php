<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookShare - Teile deine Bücher</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .book-card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .category-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #4f46e5 #1f2937;
        }
        .category-scroll::-webkit-scrollbar {
            height: 8px;
        }
        .category-scroll::-webkit-scrollbar-track {
            background: #1f2937;
            border-radius: 4px;
        }
        .category-scroll::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 4px;
        }
        .category-scroll::-webkit-scrollbar-thumb:hover {
            background: #3730a3;
        }
        .hero-background {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }
        .hero-overlay {
            background: linear-gradient(
                to right,
                rgba(0, 0, 0, 0.8) 0%,
                rgba(0, 0, 0, 0.6) 50%,
                rgba(0, 0, 0, 0.3) 100%
            );
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    @php
        $books = \App\Models\Book::with('owner')->get();
        $categorizedBooks = $books->groupBy('genre');
        $availableBooks = $books->where('status', 'verfügbar');
        $popularBooks = $books->sortByDesc('created_at')->take(8);
        $recentBooks = $books->sortByDesc('created_at')->take(8);
        
        // Random book for hero background
        $heroBook = $books->where('image_path', '!=', null)->random();
        $heroImageUrl = $heroBook && $heroBook->image_path && file_exists(public_path($heroBook->image_path)) 
            ? asset($heroBook->image_path) 
            : null;
    @endphp

    <!-- Hero Section with Random Book Background -->
    <section class="relative min-h-screen flex items-center justify-center hero-background" 
             @if($heroImageUrl) style="background-image: url('{{ $heroImageUrl }}')" @endif>
        
        <!-- Overlay -->
        <div class="absolute inset-0 hero-overlay"></div>
        
        <!-- Navigation -->
        <nav class="absolute top-0 left-0 right-0 z-20 p-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-2xl font-bold text-white">BookShare</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-indigo-300 transition-colors">
                            Dashboard
                        </a>
                        <a href="{{ route('books.index') }}" class="text-white hover:text-indigo-300 transition-colors">
                            Meine Bücher
                        </a>
                        <a href="{{ route('loans.index') }}" class="text-white hover:text-indigo-300 transition-colors">
                            Ausleihen
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-indigo-300 transition-colors">
                                Abmelden
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-indigo-300 transition-colors">
                            Anmelden
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-semibold transition-colors">
                            Registrieren
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            @if($heroBook)
                <div class="mb-8">
                    <span class="inline-block px-3 py-1 bg-indigo-600 text-sm rounded-full mb-4">
                        Heute empfohlen
                    </span>
                    <h1 class="text-5xl md:text-7xl font-bold mb-4 text-white">
                        {{ $heroBook->title }}
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-200 mb-6">
                        von {{ $heroBook->author }}
                    </p>
                    <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto line-clamp-3">
                        {{ $heroBook->description }}
                    </p>
                </div>
            @else
                <h1 class="text-5xl md:text-7xl font-bold mb-6 text-white">
                    Entdecke neue Welten
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-8">
                    Teile deine Bücher und entdecke neue Geschichten in deiner Community
                </p>
            @endif
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @auth
                    @if($heroBook)
                        <a href="{{ route('books.show', $heroBook) }}" class="px-8 py-4 bg-white text-gray-900 hover:bg-gray-100 rounded-lg font-semibold text-lg transition-colors">
                            Jetzt lesen
                        </a>
                    @endif
                    <a href="{{ route('books.index') }}" class="px-8 py-4 border-2 border-white hover:bg-white hover:text-gray-900 rounded-lg font-semibold text-lg transition-colors text-center">
                        Meine Bücher
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-gray-900 hover:bg-gray-100 rounded-lg font-semibold text-lg transition-colors">
                        Jetzt starten
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 border-2 border-white hover:bg-white hover:text-gray-900 rounded-lg font-semibold text-lg transition-colors text-center">
                        Anmelden
                    </a>
                @endauth
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/60 animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <!-- Book Categories Section -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-7xl mx-auto px-6">
            
            <!-- Trending Now -->
            @if($popularBooks->count() > 0)
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Beliebte Bücher</h2>
                <div class="category-scroll flex space-x-6 pb-4">
                    @foreach($popularBooks as $book)
                        <div class="book-card flex-shrink-0 w-64 bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                            <div class="h-80 bg-gray-700 flex items-center justify-center overflow-hidden">
                                @if($book->image_path && file_exists(public_path($book->image_path)))
                                    <img src="{{ asset($book->image_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center p-4 bg-gradient-to-br from-indigo-500 to-purple-600 w-full h-full flex items-center justify-center">
                                        <div>
                                            <h3 class="font-bold text-lg text-white mb-2 line-clamp-2">{{ $book->title }}</h3>
                                            <p class="text-indigo-200 text-sm">{{ $book->author }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-2 py-1 bg-indigo-600 text-xs rounded-full">{{ $book->genre }}</span>
                                    <span class="text-xs text-gray-400">{{ $book->publication_year }}</span>
                                </div>
                                <p class="text-gray-300 text-sm mb-3 line-clamp-2">{{ Str::limit($book->description, 80) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500">von {{ $book->owner->name }}</span>
                                    @if($book->status === 'verfügbar')
                                        <span class="px-2 py-1 bg-green-600 text-xs rounded-full">Verfügbar</span>
                                    @elseif($book->status === 'verliehen')
                                        <span class="px-2 py-1 bg-red-600 text-xs rounded-full">Verliehen</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-600 text-xs rounded-full">Angefragt</span>
                                    @endif
                                </div>
                                @auth
                                    <a href="{{ route('books.show', $book) }}" class="mt-3 block w-full bg-indigo-600 hover:bg-indigo-700 text-center py-2 rounded-lg font-medium transition-colors">
                                        Details ansehen
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="mt-3 block w-full bg-gray-700 hover:bg-gray-600 text-center py-2 rounded-lg font-medium transition-colors">
                                        Anmelden zum Ausleihen
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Categories -->
            @foreach($categorizedBooks as $genre => $booksInGenre)
                @if($booksInGenre->count() > 0)
                <div class="mb-16">
                    <h2 class="text-3xl font-bold mb-8 text-white">
                        @switch($genre)
                            @case('Fantasy')
                                Fantasy & Abenteuer
                                @break
                            @case('Science Fiction')
                                Science Fiction
                                @break
                            @case('Thriller')
                                Thriller & Spannung
                                @break
                            @case('Krimi')
                                Krimis
                                @break
                            @case('Klassiker')
                                Klassiker
                                @break
                            @case('Sachbuch')
                                Sachbücher
                                @break
                            @case('Ratgeber')
                                Ratgeber
                                @break
                            @case('Biografie')
                                Biografien
                                @break
                            @case('Humor')
                                Humor
                                @break
                            @case('Jugendbuch')
                                Jugendbücher
                                @break
                            @case('Kochbuch')
                                Kochen & Lifestyle
                                @break
                            @default
                                {{ $genre }}
                        @endswitch
                    </h2>
                    
                    <div class="category-scroll flex space-x-6 pb-4">
                        @foreach($booksInGenre->take(8) as $book)
                            <div class="book-card flex-shrink-0 w-64 bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                                <div class="h-80 bg-gray-700 flex items-center justify-center overflow-hidden">
                                    @if($book->image_path && file_exists(public_path($book->image_path)))
                                        <img src="{{ asset($book->image_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="text-center p-4 bg-gradient-to-br from-{{ $loop->parent->index % 2 == 0 ? 'blue' : 'purple' }}-500 to-{{ $loop->parent->index % 2 == 0 ? 'indigo' : 'pink' }}-600 w-full h-full flex items-center justify-center">
                                            <div>
                                                <h3 class="font-bold text-lg text-white mb-2 line-clamp-2">{{ $book->title }}</h3>
                                                <p class="text-blue-200 text-sm">{{ $book->author }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="px-2 py-1 bg-indigo-600 text-xs rounded-full">{{ $book->genre }}</span>
                                        <span class="text-xs text-gray-400">{{ $book->publication_year }}</span>
                                    </div>
                                    <p class="text-gray-300 text-sm mb-3 line-clamp-2">{{ Str::limit($book->description, 80) }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">von {{ $book->owner->name }}</span>
                                        @if($book->status === 'verfügbar')
                                            <span class="px-2 py-1 bg-green-600 text-xs rounded-full">Verfügbar</span>
                                        @elseif($book->status === 'verliehen')
                                            <span class="px-2 py-1 bg-red-600 text-xs rounded-full">Verliehen</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-600 text-xs rounded-full">Angefragt</span>
                                        @endif
                                    </div>
                                    @auth
                                        <a href="{{ route('books.show', $book) }}" class="mt-3 block w-full bg-indigo-600 hover:bg-indigo-700 text-center py-2 rounded-lg font-medium transition-colors">
                                            Details ansehen
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="mt-3 block w-full bg-gray-700 hover:bg-gray-600 text-center py-2 rounded-lg font-medium transition-colors">
                                            Anmelden zum Ausleihen
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach

            <!-- Call to Action -->
            <div class="text-center py-16">
                <h2 class="text-4xl font-bold mb-6 text-white">Bereit zum Teilen?</h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Werde Teil unserer Buchliebhaber-Community und entdecke neue Welten durch das Teilen von Büchern.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-semibold text-lg transition-colors inline-block">
                        Jetzt kostenlos registrieren
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-6 h-6 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xl font-bold text-white">BookShare</span>
                    </div>
                    <p class="text-gray-400">
                        Die Plattform für Buchliebhaber zum Teilen und Entdecken.
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Community</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-indigo-400">Über uns</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Blog</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Hilfe</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Bücher</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-indigo-400">Alle Kategorien</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Neue Bücher</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Beliebte Bücher</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Kontakt</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-indigo-400">Support</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Datenschutz</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Impressum</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 BookShare. Alle Rechte vorbehalten.</p>
            </div>
        </div>
    </footer>
</body>
</html>
