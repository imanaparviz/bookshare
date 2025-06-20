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
            transform: scale(1.05);
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
            scroll-snap-type: x mandatory;
        }
        .section-scroll::-webkit-scrollbar {
            display: none;
        }
        .book-card {
            scroll-snap-align: start;
        }
        
        /* Einheitliche Kartengrößen für alle Breakpoints */
        .book-card-small {
            width: 130px;
            min-width: 130px;
            max-width: 130px;
        }
        .book-card-medium { 
            width: 160px;
            min-width: 160px;
            max-width: 160px;
        }
        .book-card-large {
            width: 180px;
            min-width: 180px;
            max-width: 180px;
        }
        
        /* Responsive Breakpoints */
        @media (max-width: 640px) {
            .book-card-small { width: 110px; min-width: 110px; max-width: 110px; }
            .book-card-medium { width: 120px; min-width: 120px; max-width: 120px; }
            .book-card-large { width: 140px; min-width: 140px; max-width: 140px; }
            .section-scroll { gap: 0.5rem !important; }
        }
        
        @media (min-width: 641px) and (max-width: 768px) {
            .book-card-small { width: 120px; min-width: 120px; max-width: 120px; }
            .book-card-medium { width: 140px; min-width: 140px; max-width: 140px; }
            .book-card-large { width: 160px; min-width: 160px; max-width: 160px; }
            .section-scroll { gap: 0.75rem !important; }
        }
        
        @media (min-width: 769px) and (max-width: 1024px) {
            .book-card-small { width: 140px; min-width: 140px; max-width: 140px; }
            .book-card-medium { width: 170px; min-width: 170px; max-width: 170px; }
            .book-card-large { width: 190px; min-width: 190px; max-width: 190px; }
        }
        
        /* Einheitliche Bildgrößen */
        .book-cover-small { height: 160px; }
        .book-cover-medium { height: 200px; }
        .book-cover-large { height: 240px; }
        
        @media (max-width: 640px) {
            .book-cover-small { height: 140px; }
            .book-cover-medium { height: 160px; }
            .book-cover-large { height: 180px; }
        }
        
        @media (min-width: 641px) and (max-width: 768px) {
            .book-cover-small { height: 150px; }
            .book-cover-medium { height: 180px; }
            .book-cover-large { height: 200px; }
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
            position: relative;
        }
        .netflix-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Figtree', sans-serif;
        }
        .scroll-container {
            position: relative;
        }
        .scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 20;
            transition: all 0.3s ease;
            opacity: 0.7;
        }
        .scroll-btn:hover {
            background: rgba(0, 0, 0, 0.9);
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
            border-color: rgba(255, 255, 255, 0.4);
        }
        .scroll-btn-left {
            left: -25px;
        }
        .scroll-btn-right {
            right: -25px;
        }
        .scroll-btn svg {
            width: 20px;
            height: 20px;
        }
        @media (max-width: 768px) {
            .scroll-btn {
                width: 40px;
                height: 40px;
            }
            .scroll-btn svg {
                width: 16px;
                height: 16px;
            }
            .scroll-btn-left { left: -20px; }
            .scroll-btn-right { right: -20px; }
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
        $recentBooks = $books->sortByDesc('created_at')->take(8);
        
        // Top rated books (only books with ratings >= 4.0)
        $topRatedBooks = $books->filter(function($book) {
            return $book->ratings_count > 0 && $book->ratings_avg_rating >= 4.0;
        })->sortByDesc('ratings_avg_rating')->take(6);
        
        // Better popular books (books with any ratings, sorted by avg rating)
        $popularBooks = $books->filter(function($book) {
            return $book->ratings_count > 0;
        })->sortByDesc('ratings_avg_rating')->take(8);
        
        // If no rated books, show latest books as popular
        if ($popularBooks->isEmpty()) {
            $popularBooks = $books->sortByDesc('created_at')->take(8);
        }
        
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
                            TOPBEWERTET
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
                               class="px-8 py-4 bg-yellow-500 text-black hover:bg-yellow-600 rounded-lg font-bold text-lg transition-all transform hover:scale-105 shadow-lg">
                                Details ansehen
                            </a>
                            @auth
                                @if($heroBook->status === 'verfügbar' && $heroBook->owner_id !== auth()->id())
                                    <form method="POST" action="{{ route('loans.store') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $heroBook->id }}">
                                        <button type="submit" 
                                                class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-black rounded-lg font-bold text-lg transition-all">
                                            Ausleihen
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('register') }}" 
                                   class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-black rounded-lg font-bold text-lg transition-all">
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
                <h2 class="netflix-title">Bestbewertet</h2>
                <div class="scroll-container">
                    <button class="scroll-btn scroll-btn-left" onclick="scrollContainer(this, 'left')">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div class="section-scroll flex gap-4 pb-4" data-scroll-container>
                    @foreach($topRatedBooks as $book)
                        <div class="book-card book-card-large relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer"
                             @click="window.location.href='{{ route('books.show', $book) }}'">
                            
                            <!-- Rating Badge -->
                            <div class="floating-rating">
                                <svg class="star filled w-4 h-4" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-white font-bold text-sm">{{ number_format($book->ratings_avg_rating, 1) }}</span>
                            </div>
                            
                            <!-- Book Cover -->
                            <div class="book-cover-large bg-gray-700 overflow-hidden relative">
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
                    <button class="scroll-btn scroll-btn-right" onclick="scrollContainer(this, 'right')">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            <!-- Popular Books -->
            @if($popularBooks->count() > 0)
            <div class="netflix-row">
                <h2 class="netflix-title">Beliebt</h2>
                <div class="scroll-container">
                    <button class="scroll-btn scroll-btn-left" onclick="scrollContainer(this, 'left')">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div class="section-scroll flex gap-4 pb-4" data-scroll-container>
                    @foreach($popularBooks as $book)
                        <div class="book-card book-card-medium relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer"
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
                            <div class="book-cover-medium bg-gray-700 overflow-hidden relative">
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
                    <button class="scroll-btn scroll-btn-right" onclick="scrollContainer(this, 'right')">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            <!-- Recent Books -->
            @if($recentBooks->count() > 0)
            <div class="netflix-row">
                <h2 class="netflix-title">Neu hinzugefügt</h2>
                <div class="scroll-container">
                    <button class="scroll-btn scroll-btn-left" onclick="scrollContainer(this, 'left')">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div class="section-scroll flex gap-4 pb-4" data-scroll-container>
                    @foreach($recentBooks as $book)
                        <div class="book-card book-card-small relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer"
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
                            <div class="book-cover-small bg-gray-700 overflow-hidden relative">
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
                    <button class="scroll-btn scroll-btn-right" onclick="scrollContainer(this, 'right')">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            <!-- Books by Genre -->
            @if($categorizedBooks->count() > 0)
                @foreach($categorizedBooks->take(3) as $genre => $genreBooks)
                    @if($genreBooks->count() > 0)
                    <div class="netflix-row">
                        <h2 class="netflix-title">{{ ucfirst($genre) }}</h2>
                        <div class="scroll-container">
                            <button class="scroll-btn scroll-btn-left" onclick="scrollContainer(this, 'left')">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <div class="section-scroll flex gap-4 pb-4" data-scroll-container>
                            @foreach($genreBooks->take(8) as $book)
                                <div class="book-card book-card-small relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer"
                                     @click="window.location.href='{{ route('books.show', $book) }}'">
                                    
                                    <!-- Genre Badge -->
                                    <div class="absolute top-2 left-2 z-10">
                                        <span class="px-2 py-1 bg-blue-600 text-white text-xs font-bold rounded">{{ strtoupper($genre) }}</span>
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
                                    <div class="book-cover-small bg-gray-700 overflow-hidden relative">
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
                                            <h3 class="font-bold text-white text-sm mb-1">{{ Str::limit($book->title, 20) }}</h3>
                                            <p class="text-gray-300 text-xs mb-2">{{ $book->author }}</p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs px-2 py-1 bg-green-500 text-white rounded font-medium">
                                                    {{ ucfirst($book->status) }}
                                                </span>
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
                            <button class="scroll-btn scroll-btn-right" onclick="scrollContainer(this, 'right')">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif
                @endforeach
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
                            Buch hinzufügen
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
                © 2025 BookShare. Made with love for book lovers.
            </p>
        </div>
    </footer>

    <script>
        // Scroll container function for buttons
        function scrollContainer(button, direction) {
            const container = button.parentNode.querySelector('[data-scroll-container]');
            const scrollAmount = container.clientWidth * 0.8; // Scroll 80% of visible width
            
            if (direction === 'left') {
                container.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            } else {
                container.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }
        }

        // Smooth scrolling for horizontal sections (mouse drag)
        document.querySelectorAll('.section-scroll').forEach(section => {
            let isDown = false;
            let startX;
            let scrollLeft;

            section.addEventListener('mousedown', (e) => {
                isDown = true;
                section.style.cursor = 'grabbing';
                startX = e.pageX - section.offsetLeft;
                scrollLeft = section.scrollLeft;
            });

            section.addEventListener('mouseleave', () => {
                isDown = false;
                section.style.cursor = 'grab';
            });

            section.addEventListener('mouseup', () => {
                isDown = false;
                section.style.cursor = 'grab';
            });

            section.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - section.offsetLeft;
                const walk = (x - startX) * 2;
                section.scrollLeft = scrollLeft - walk;
            });

            // Set initial cursor
            section.style.cursor = 'grab';
        });

        // Show/hide scroll buttons based on scroll position
        document.querySelectorAll('.scroll-container').forEach(container => {
            const scrollContainer = container.querySelector('[data-scroll-container]');
            const leftBtn = container.querySelector('.scroll-btn-left');
            const rightBtn = container.querySelector('.scroll-btn-right');

            function updateButtons() {
                const isAtStart = scrollContainer.scrollLeft <= 0;
                const isAtEnd = scrollContainer.scrollLeft >= (scrollContainer.scrollWidth - scrollContainer.clientWidth);
                
                leftBtn.style.opacity = isAtStart ? '0.3' : '0.7';
                rightBtn.style.opacity = isAtEnd ? '0.3' : '0.7';
                leftBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';
                rightBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
            }

            scrollContainer.addEventListener('scroll', updateButtons);
            window.addEventListener('resize', updateButtons);
            updateButtons(); // Initial check
        });
    </script>
</body>
</html>
