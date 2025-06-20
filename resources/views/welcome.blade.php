<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookShare - Teile deine Bücher</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .book-card {
            transition: all 0.3s ease;
        }
        .book-card:hover {
            transform: scale(1.08);
            z-index: 10;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .section-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .section-scroll::-webkit-scrollbar {
            display: none;
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
                rgba(0, 0, 0, 0.9) 0%,
                rgba(0, 0, 0, 0.7) 50%,
                rgba(0, 0, 0, 0.3) 100%
            );
        }
        .rating-stars {
            display: flex;
            align-items: center;
        }
        .star {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }
        .star.filled {
            color: #fbbf24;
        }
        .star.empty {
            color: #6b7280;
        }
        .floating-rating {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            padding: 4px 8px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .book-hover-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
            padding: 20px 16px 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .book-card:hover .book-hover-info {
            opacity: 1;
        }
        .netflix-row {
            margin-bottom: 3rem;
        }
        .netflix-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }
        .quick-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 8px;
        }
        .quick-rating .star {
            width: 20px;
            height: 20px;
            cursor: pointer;
            transition: color 0.2s ease;
        }
    </style>
</head>
<body class="bg-gray-900 text-white" x-data="{ showQuickRating: null }">
    @php
        $books = \App\Models\Book::with(['owner', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->get();
        $categorizedBooks = $books->groupBy('genre');
        $availableBooks = $books->where('status', 'verfügbar');
        $popularBooks = $books->sortByDesc('created_at')->take(8);
        $recentBooks = $books->sortByDesc('created_at')->take(8);
        
        // Top rated books (only books with ratings >= 4.0)
        $topRatedBooks = $books->filter(function($book) {
            return $book->ratings_count > 0 && $book->ratings_avg_rating >= 4.0;
        })->sortByDesc('ratings_avg_rating')->take(8);
        
        // Random book for hero background
        $heroBook = $books->where('image_path', '!=', null)->random();
        $heroImageUrl = null;
        if ($heroBook && $heroBook->image_path) {
            if (str_starts_with($heroBook->image_path, 'images/')) {
                // Static seeded images
                $heroImageUrl = asset($heroBook->image_path);
            } else {
                // User uploaded images
                $heroImageUrl = asset('storage/' . $heroBook->image_path);
            }
        }
    @endphp

    <!-- Hero Section -->
    <section class="relative min-h-screen hero-background" 
             @if($heroImageUrl) style="background-image: url('{{ $heroImageUrl }}')" @endif>
        
        <!-- Overlay -->
        <div class="absolute inset-0 hero-overlay"></div>
        
        <!-- Navigation -->
        <nav class="absolute top-0 left-0 right-0 z-20 p-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <svg class="w-10 h-10 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-3xl font-bold text-white">BookShare</span>
                </div>
                
                <div class="flex items-center space-x-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-yellow-300 transition-colors font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('books.index') }}" class="text-white hover:text-yellow-300 transition-colors font-medium">
                            Meine Bücher
                        </a>
                        <a href="{{ route('ratings.user') }}" class="text-white hover:text-yellow-300 transition-colors font-medium">
                            Meine Bewertungen
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-yellow-300 transition-colors font-medium">
                                Abmelden
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-yellow-300 transition-colors font-medium">
                            Anmelden
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-black rounded-lg font-bold transition-colors">
                            Registrieren
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div class="relative z-10 flex items-center min-h-screen">
            <div class="max-w-4xl mx-auto px-6">
                @if($heroBook)
                    <div class="max-w-2xl">
                        <span class="inline-block px-4 py-2 bg-yellow-500 text-black text-sm font-bold rounded-full mb-6">
                            ⭐ TOPBEWERTET
                        </span>
                        <h1 class="text-4xl md:text-6xl font-bold mb-4 text-white leading-tight">
                            {{ $heroBook->title }}
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-200 mb-4">
                            von {{ $heroBook->author }}
                        </p>
                        
                        <!-- Hero Rating -->
                        @if($heroBook->ratings_count > 0)
                            <div class="flex items-center gap-4 mb-6">
                                <div class="flex items-center gap-2">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="star {{ $i <= round($heroBook->ratings_avg_rating) ? 'filled' : 'empty' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-2xl font-bold text-yellow-400">{{ number_format($heroBook->ratings_avg_rating, 1) }}</span>
                                    <span class="text-gray-300">({{ $heroBook->ratings_count }} {{ $heroBook->ratings_count === 1 ? 'Bewertung' : 'Bewertungen' }})</span>
                                </div>
                            </div>
                        @endif
                        
                        @if($heroBook->description)
                            <p class="text-lg text-gray-300 mb-8 leading-relaxed max-w-xl">
                                {{ Str::limit($heroBook->description, 200) }}
                            </p>
                        @endif
                        
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('books.show', $heroBook) }}" 
                               class="px-8 py-4 bg-white text-black hover:bg-gray-100 rounded-lg font-bold text-lg transition-all transform hover:scale-105 shadow-lg">
                                ▶ Details ansehen
                            </a>
                            @auth
                                @if($heroBook->status === 'verfügbar' && $heroBook->owner_id !== auth()->id())
                                    <form method="POST" action="{{ route('loans.store') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $heroBook->id }}">
                                        <button type="submit" 
                                                class="px-8 py-4 border-2 border-white hover:bg-white hover:text-black rounded-lg font-bold text-lg transition-all">
                                            📚 Ausleihen
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('register') }}" 
                                   class="px-8 py-4 border-2 border-white hover:bg-white hover:text-black rounded-lg font-bold text-lg transition-all">
                                    Registrieren zum Ausleihen
                                </a>
                            @endauth
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <h1 class="text-5xl md:text-7xl font-bold mb-6 text-white">
                            Entdecke neue Welten
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-200 mb-8 max-w-3xl mx-auto">
                            Teile deine Bücher und entdecke neue Geschichten in deiner Community
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center">
                            @auth
                                <a href="{{ route('books.index') }}" class="px-8 py-4 bg-yellow-500 text-black hover:bg-yellow-600 rounded-lg font-bold text-lg transition-all">
                                    Meine Bücher
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="px-8 py-4 bg-yellow-500 text-black hover:bg-yellow-600 rounded-lg font-bold text-lg transition-all">
                                    Jetzt starten
                                </a>
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Netflix-Style Book Sections -->
    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-6">
            
            <!-- Top Rated Books -->
            @if($topRatedBooks->count() > 0)
            <div class="netflix-row">
                <h2 class="netflix-title">🏆 Bestbewertet</h2>
                <div class="section-scroll flex gap-4 pb-4">
                    @foreach($topRatedBooks as $book)
                        <div class="book-card relative flex-shrink-0 w-72 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer"
                             @click="window.location.href='{{ route('books.show', $book) }}'">
                            
                            <!-- Rating Badge -->
                            <div class="floating-rating">
                                <svg class="star filled w-4 h-4" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-white font-bold text-sm">{{ number_format($book->ratings_avg_rating, 1) }}</span>
                            </div>
                            
                            <!-- Book Cover -->
                            <div class="h-96 bg-gray-700 overflow-hidden relative">
                                @if($book->image_path)
                                    @if(str_starts_with($book->image_path, 'images/'))
                                        <img src="{{ asset($book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('storage/' . $book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full h-full object-cover">
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Hover Info -->
                                <div class="book-hover-info">
                                    <h3 class="font-bold text-white text-lg mb-1">{{ Str::limit($book->title, 30) }}</h3>
                                    <p class="text-gray-300 text-sm mb-2">{{ $book->author }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs px-3 py-1 bg-yellow-500 text-black rounded-full font-bold">
                                            {{ ucfirst($book->status) }}
                                        </span>
                                        <span class="text-gray-400 text-xs">{{ $book->ratings_count }} Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Popular Books -->
            @if($popularBooks->count() > 0)
            <div class="netflix-row">
                <h2 class="netflix-title">🔥 Beliebt</h2>
                <div class="section-scroll flex gap-4 pb-4">
                    @foreach($popularBooks as $book)
                        <div class="book-card relative flex-shrink-0 w-64 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer"
                             @click="window.location.href='{{ route('books.show', $book) }}'">
                            
                            <!-- Rating Badge -->
                            @if($book->ratings_count > 0)
                                <div class="floating-rating">
                                    <svg class="star filled w-4 h-4" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-white font-bold text-sm">{{ number_format($book->ratings_avg_rating ?: 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <!-- Book Cover -->
                            <div class="h-80 bg-gray-700 overflow-hidden relative">
                                @if($book->image_path)
                                    @if(str_starts_with($book->image_path, 'images/'))
                                        <img src="{{ asset($book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('storage/' . $book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full h-full object-cover">
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Hover Info -->
                                <div class="book-hover-info">
                                    <h3 class="font-bold text-white text-lg mb-1">{{ Str::limit($book->title, 25) }}</h3>
                                    <p class="text-gray-300 text-sm mb-2">{{ $book->author }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs px-2 py-1 bg-green-500 text-white rounded font-medium">
                                            {{ ucfirst($book->status) }}
                                        </span>
                                        @if($book->ratings_count > 0)
                                            <span class="text-gray-400 text-xs">{{ $book->ratings_count }} Reviews</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Books -->
            @if($recentBooks->count() > 0)
            <div class="netflix-row">
                <h2 class="netflix-title">🆕 Neu hinzugefügt</h2>
                <div class="section-scroll flex gap-4 pb-4">
                    @foreach($recentBooks as $book)
                        <div class="book-card relative flex-shrink-0 w-56 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer"
                             @click="window.location.href='{{ route('books.show', $book) }}'">
                            
                            <!-- New Badge -->
                            <div class="absolute top-2 left-2 z-10">
                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">NEU</span>
                            </div>
                            
                            <!-- Rating Badge -->
                            @if($book->ratings_count > 0)
                                <div class="floating-rating">
                                    <svg class="star filled w-4 h-4" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-white font-bold text-sm">{{ number_format($book->ratings_avg_rating ?: 0, 1) }}</span>
                                </div>
                            @endif
                            
                            <!-- Book Cover -->
                            <div class="h-72 bg-gray-700 overflow-hidden relative">
                                @if($book->image_path)
                                    @if(str_starts_with($book->image_path, 'images/'))
                                        <img src="{{ asset($book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('storage/' . $book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full h-full object-cover">
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Hover Info -->
                                <div class="book-hover-info">
                                    <h3 class="font-bold text-white mb-1">{{ Str::limit($book->title, 20) }}</h3>
                                    <p class="text-gray-300 text-sm mb-2">{{ $book->author }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">{{ $book->created_at->diffForHumans() }}</span>
                                        @if($book->ratings_count > 0)
                                            <span class="text-gray-400 text-xs">{{ $book->ratings_count }} Reviews</span>
                                        @else
                                            <span class="text-gray-500 text-xs">Noch keine Bewertung</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Call to Action -->
            <div class="text-center py-16">
                <h2 class="text-4xl font-bold mb-6">Teile deine Lieblingsbücher</h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Werde Teil unserer Community und entdecke neue Bücher durch Bewertungen anderer Leser
                </p>
                @auth
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('books.create') }}" 
                           class="px-8 py-4 bg-yellow-500 text-black hover:bg-yellow-600 rounded-lg font-bold text-lg transition-all">
                            📚 Buch hinzufügen
                        </a>
                        <a href="{{ route('books.index') }}" 
                           class="px-8 py-4 border-2 border-white hover:bg-white hover:text-black rounded-lg font-bold text-lg transition-all">
                            Meine Bücher verwalten
                        </a>
                    </div>
                @else
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 bg-yellow-500 text-black hover:bg-yellow-600 rounded-lg font-bold text-lg transition-all">
                        Kostenlos registrieren
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black py-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-2xl font-bold">BookShare</span>
            </div>
            <p class="text-gray-400 mb-4">
                Teile Wissen. Entdecke Geschichten. Baue Gemeinschaft auf.
            </p>
            <div class="flex flex-wrap gap-6 justify-center text-sm text-gray-500">
                <a href="/about" class="hover:text-white transition-colors">Über uns</a>
                <a href="/privacy" class="hover:text-white transition-colors">Datenschutz</a>
                <a href="/contact" class="hover:text-white transition-colors">Kontakt</a>
                <a href="/help" class="hover:text-white transition-colors">Hilfe</a>
            </div>
            <p class="text-gray-600 text-sm mt-6">
                © 2025 BookShare. Made with ❤️ for book lovers.
            </p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for horizontal sections
        document.querySelectorAll('.section-scroll').forEach(section => {
            let isDown = false;
            let startX;
            let scrollLeft;

            section.addEventListener('mousedown', (e) => {
                isDown = true;
                startX = e.pageX - section.offsetLeft;
                scrollLeft = section.scrollLeft;
            });

            section.addEventListener('mouseleave', () => {
                isDown = false;
            });

            section.addEventListener('mouseup', () => {
                isDown = false;
            });

            section.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - section.offsetLeft;
                const walk = (x - startX) * 2;
                section.scrollLeft = scrollLeft - walk;
            });
        });
    </script>
</body>
</html>
