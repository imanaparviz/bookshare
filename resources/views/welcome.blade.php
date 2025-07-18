<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        /* Replace all book placeholder icons with hearts */
        svg[viewBox="0 0 20 20"] path[d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"] {
            display: none;
        }
        svg[viewBox="0 0 20 20"]:has(path[d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"])::before {
            content: "❤️";
            font-size: 3rem;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Custom styles for the collapsible sidebar - Dark Mode Development */
        .user-sidebar {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            backdrop-filter: blur(15px);
            /* Dark background matching website theme */
            background: rgba(17, 24, 39, 0.95); /* gray-900 like main app */
            border-left: 1px solid rgba(75, 85, 99, 0.2); /* gray-600 */
        }
        
        .user-sidebar:hover,
        .user-sidebar.show {
            transform: translateX(0);
            box-shadow: -20px 0 60px rgba(0, 0, 0, 0.5);
        }
        

        
        .user-sidebar ul li {
            transition: all 0.3s ease;
        }
        
        .user-sidebar ul li:hover {
            transform: translateX(8px);
            background: rgba(59, 130, 246, 0.15); /* blue hover */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }
        
        .profile-avatar {
            transition: transform 0.3s ease;
        }
        
        .profile-avatar:hover {
            transform: scale(1.1) rotate(5deg);
        }
        
        /* All text colors light for dark mode development */
        .user-sidebar h3,
        .user-sidebar h4,
        .user-sidebar .text-lg {
            color: rgb(248, 250, 252) !important; /* slate-50 - very light */
        }
        
        .user-sidebar .text-sm,
        .user-sidebar .text-xs,
        .user-sidebar span {
            color: rgb(203, 213, 225) !important; /* slate-300 - light */
        }
        
        .user-sidebar a {
            color: rgb(226, 232, 240) !important; /* slate-200 - light */
        }
        
        .user-sidebar a:hover {
            color: rgb(96, 165, 250) !important; /* blue-400 - bright blue */
        }
        
        /* Stat cards with dark background */
        .user-sidebar .bg-slate-700\/60 {
            background: rgba(31, 41, 55, 0.7) !important; /* gray-800 */
            border-color: rgba(75, 85, 99, 0.3) !important; /* gray-600 */
        }
        
        /* Header with dark gradient */
        .user-sidebar .bg-gradient-to-r {
            background: linear-gradient(to right, rgb(31, 41, 55), rgb(17, 24, 39)) !important; /* gray-800 to gray-900 */
        }
        
        /* Borders with dark theme */
        .user-sidebar .border-slate-700\/30 {
            border-color: rgba(75, 85, 99, 0.3) !important; /* gray-600 */
        }
        
        /* Status indicator colors */
        .user-sidebar .text-blue-400,
        .user-sidebar .text-emerald-400,
        .user-sidebar .text-amber-400 {
            color: rgb(96, 165, 250) !important; /* blue-400 */
        }
        
        .user-sidebar .text-emerald-400 {
            color: rgb(52, 211, 153) !important; /* emerald-400 */
        }
        
        .user-sidebar .text-amber-400 {
            color: rgb(251, 191, 36) !important; /* amber-400 */
        }
        
        /* Make sure all text elements are light */
        .user-sidebar * {
            color: rgb(203, 213, 225) !important; /* default light color */
        }
        
        /* Override for specific bright elements */
        .user-sidebar h3,
        .user-sidebar h4 {
            color: rgb(248, 250, 252) !important; /* brightest for headings */
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
        
        // Random verfügbares book for hero background (nur verfügbare Bücher zeigen)
        $availableBooksWithImages = $books->where('image_path', '!=', null)->where('status', 'verfügbar');
        $heroBook = $availableBooksWithImages->isNotEmpty() ? $availableBooksWithImages->random() : null;
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
                    <span class="text-4xl">❤️</span>
                    <span class="text-3xl font-bold text-white">BookShare</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-yellow-300 transition-colors font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('books.index') }}" class="text-white hover:text-yellow-300 transition-colors font-medium">
                            Meine Bücher
                        </a>
                        <a href="{{ route('ratings.user', auth()->user()) }}" class="text-white hover:text-yellow-300 transition-colors font-medium">
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
                        <h1 class="text-4xl md:text-6xl font-bold mb-4 text-white leading-tight hero-book-title">
                            {{ $heroBook->title }}
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-200 mb-4 hero-book-author">
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
                            <p class="text-lg text-gray-300 mb-8 leading-relaxed max-w-xl hero-book-description">
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
                                    <a href="{{ route('books.show', $heroBook) }}" 
                                       class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-black rounded-lg font-bold text-lg transition-all hero-loan-link">
                                        Ausleihen
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}" 
                                   class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-black rounded-lg font-bold text-lg transition-all hero-register-link">
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
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <div class="book-card book-card-large relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer hover:scale-105 transition-transform duration-300">
                            
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
                        </a>
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
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <div class="book-card book-card-medium relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer hover:scale-105 transition-transform duration-300">
                            
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
                        </a>
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
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <div class="book-card book-card-small relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer hover:scale-105 transition-transform duration-300">
                            
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
                        </a>
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
                                <a href="{{ route('books.show', $book) }}" class="block">
                                    <div class="book-card book-card-small relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer hover:scale-105 transition-transform duration-300">
                                    
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
                                </a>
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

            <!-- AI Recommendations Section -->
            @auth
                @if(isset($aiRecommendations) && $aiRecommendations->count() > 0)
                <div class="netflix-row">
                    <h2 class="netflix-title">🤖 OPENAI-EMPFEHLUNGEN FÜR SIE</h2>
                    <div class="scroll-container">
                        <button class="scroll-btn scroll-btn-left" onclick="scrollContainer(this, 'left')">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div class="section-scroll flex gap-4 pb-4" data-scroll-container>
                        @foreach($aiRecommendations as $recommendation)
                            <a href="{{ route('books.show', $recommendation['book']) }}" class="block">
                                <div class="book-card book-card-medium relative flex-shrink-0 bg-gray-800 rounded-xl overflow-hidden shadow-xl cursor-pointer hover:scale-105 transition-transform duration-300">
                                
                                <!-- AI Badge -->
                                <div class="absolute top-2 left-2 z-10">
                                    <span class="px-2 py-1 bg-purple-600 text-white text-xs font-bold rounded">🤖 KI</span>
                                </div>
                                
                                <!-- AI Score Badge -->
                                <div class="floating-rating">
                                    <span class="text-white font-bold text-sm">{{ round($recommendation['score'], 1) }}</span>
                                </div>
                                
                                <!-- Book Cover -->
                                <div class="book-cover-medium bg-gray-700 overflow-hidden relative">
                                    @if($recommendation['book']->image_path)
                                        @if(str_starts_with($recommendation['book']->image_path, 'images/'))
                                            <img src="{{ asset($recommendation['book']->image_path) }}" 
                                                 alt="{{ $recommendation['book']->title }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('storage/' . $recommendation['book']->image_path) }}" 
                                                 alt="{{ $recommendation['book']->title }}" 
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
                                        <h3 class="font-bold text-white text-lg mb-1">{{ Str::limit($recommendation['book']->title, 25) }}</h3>
                                        <p class="text-gray-300 text-sm mb-2">{{ $recommendation['book']->author }}</p>
                                        <p class="text-purple-300 text-xs mb-2">🤖 {{ $recommendation['reason'] }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs px-2 py-1 bg-purple-500 text-white rounded font-medium">
                                                {{ ucfirst($recommendation['book']->status) }}
                                            </span>
                                            @if($recommendation['book']->ratings_count > 0)
                                                <span class="text-gray-400 text-xs">{{ $recommendation['book']->ratings_count }} Reviews</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
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
            @endauth

            <!-- Special OpenAI Analysis Section -->
            @auth
            <div class="bg-gradient-to-r from-purple-900 via-blue-900 to-indigo-900 rounded-2xl p-8 mb-16 border border-purple-500 shadow-2xl">
                <div class="text-center">
                    <div class="mb-6">
                        <h2 class="text-4xl font-bold mb-4 bg-gradient-to-r from-yellow-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                            🤖 OpenAI Vollanalyse
                        </h2>
                        <p class="text-xl text-gray-300 mb-6">
                            Lassen Sie OpenAI GPT-4 Ihre komplette Buchhistorie analysieren und maßgeschneiderte Empfehlungen erstellen
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-black/30 p-4 rounded-lg border border-purple-400">
                            <div class="text-purple-400 text-2xl mb-2">📊</div>
                            <h3 class="font-bold text-white mb-1">Vollanalyse</h3>
                            <p class="text-gray-300 text-sm">Bewertungen, eigene Bücher, Ausleihverlauf</p>
                        </div>
                        <div class="bg-black/30 p-4 rounded-lg border border-blue-400">
                            <div class="text-blue-400 text-2xl mb-2">🎯</div>
                            <h3 class="font-bold text-white mb-1">Intelligente Empfehlungen</h3>
                            <p class="text-gray-300 text-sm">GPT-4 powered Buchvorschläge</p>
                        </div>
                        <div class="bg-black/30 p-4 rounded-lg border border-pink-400">
                            <div class="text-pink-400 text-2xl mb-2">✨</div>
                            <h3 class="font-bold text-white mb-1">Personalisiert</h3>
                            <p class="text-gray-300 text-sm">Basierend auf Ihrem einzigartigen Geschmack</p>
                        </div>
                    </div>

                    <button id="aiAnalysisBtn" 
                            class="px-10 py-5 bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 hover:from-purple-700 hover:via-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-xl transition-all duration-300 transform hover:scale-105 shadow-lg border border-purple-400">
                        <span class="flex items-center justify-center">
                            <svg class="w-6 h-6 mr-3 animate-spin hidden" id="aiSpinner" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="31.416" stroke-dashoffset="31.416" class="animate-spin"></circle>
                            </svg>
                            <span id="aiButtonText">🤖 OpenAI Analyse starten</span>
                        </span>
                    </button>

                    <!-- Results Area -->
                    <div id="aiResults" class="mt-8 hidden">
                        <h3 class="text-2xl font-bold text-yellow-400 mb-6">🎯 Ihre OpenAI Empfehlungen</h3>
                        <div id="aiRecommendationsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- AI recommendations will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            @endauth

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
                           class="px-8 py-4 border-2 border-white text-white hover:bg-white hover:text-black rounded-lg font-bold text-lg transition-all">
                            Meine Bücher verwalten
                        </a>
                        <a href="{{ route('recommendations') }}" 
                           class="px-8 py-4 border-2 border-purple-500 text-purple-300 hover:bg-purple-500 hover:text-white rounded-lg font-bold text-lg transition-all">
                            🤖 Mehr KI-Empfehlungen
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
                <span class="text-3xl mr-3">❤️</span>
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
         <!--    <p class="text-gray-600 text-sm mt-6">
                © 2025 BookShare. Made with love for book lovers.
            </p> -->
        </div>
    </footer>

    <script>
        // Set authentication status for JavaScript
        @if(auth()->check())
            window.isAuthenticated = true;
            window.currentUserId = {{ auth()->id() }};
        @else
            window.isAuthenticated = false;
            window.currentUserId = null;
        @endif
        
        // OpenAI Analysis Function with extensive logging
        async function performAIAnalysis() {
            console.log('🚀 [OpenAI] === STARTING AI ANALYSIS ===');
            console.log('🚀 [OpenAI] Timestamp:', new Date().toISOString());
            
            const btn = document.getElementById('aiAnalysisBtn');
            const spinner = document.getElementById('aiSpinner');
            const buttonText = document.getElementById('aiButtonText');
            const resultsArea = document.getElementById('aiResults');
            const grid = document.getElementById('aiRecommendationsGrid');

            // Show loading state
            btn.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.textContent = 'OpenAI analysiert...';
            
            console.log('🚀 [OpenAI] UI State: Loading activated');
            console.log('🚀 [OpenAI] Button disabled:', btn.disabled);
            console.log('🚀 [OpenAI] Spinner visible:', !spinner.classList.contains('hidden'));
            
            try {
                console.log('🚀 [OpenAI] --- PREPARING API REQUEST ---');
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('🚀 [OpenAI] CSRF token found:', !!csrfToken);
                console.log('🚀 [OpenAI] CSRF token (first 10 chars):', csrfToken ? csrfToken.substring(0, 10) + '...' : 'MISSING');
                
                const requestUrl = '/api/advanced-ai-recommendations';
                console.log('🚀 [OpenAI] Request URL:', requestUrl);
                console.log('🚀 [OpenAI] Request method: GET');
                
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken || ''
                };
                console.log('🚀 [OpenAI] Request headers:', headers);

                console.log('🚀 [OpenAI] --- SENDING REQUEST TO SERVER ---');
                const startTime = performance.now();
                
                const response = await fetch(requestUrl, {
                    method: 'GET',
                    headers: headers
                });

                const requestDuration = performance.now() - startTime;
                console.log('🚀 [OpenAI] Request completed in:', Math.round(requestDuration), 'ms');
                console.log('🚀 [OpenAI] Response status:', response.status);
                console.log('🚀 [OpenAI] Response status text:', response.statusText);
                console.log('🚀 [OpenAI] Response headers:', Object.fromEntries(response.headers.entries()));

                if (!response.ok) {
                    console.error('❌ [OpenAI] HTTP Error Details:');
                    console.error('❌ [OpenAI] Status:', response.status);
                    console.error('❌ [OpenAI] Status Text:', response.statusText);
                    console.error('❌ [OpenAI] URL:', response.url);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                console.log('🚀 [OpenAI] --- PARSING RESPONSE ---');
                const data = await response.json();
                console.log('🚀 [OpenAI] Raw response data:', data);
                console.log('🚀 [OpenAI] Response type:', typeof data);
                console.log('🚀 [OpenAI] Success flag:', data.success);
                console.log('🚀 [OpenAI] Total recommendations found:', data.total_found);
                console.log('🚀 [OpenAI] Analysis type:', data.analysis_type);
                // Convert recommendations to array if it's an object
                let recommendationsArray = [];
                if (data.recommendations) {
                    if (Array.isArray(data.recommendations)) {
                        recommendationsArray = data.recommendations;
                    } else if (typeof data.recommendations === 'object') {
                        // Convert object to array
                        recommendationsArray = Object.values(data.recommendations);
                    }
                }
                
                console.log('🚀 [OpenAI] Recommendations array length:', recommendationsArray.length);
                console.log('🚀 [OpenAI] Recommendations type:', typeof data.recommendations);
                console.log('🚀 [OpenAI] Is array:', Array.isArray(data.recommendations));
                
                if (recommendationsArray.length > 0) {
                    console.log('🚀 [OpenAI] --- ANALYZING INDIVIDUAL RECOMMENDATIONS ---');
                    recommendationsArray.forEach((rec, index) => {
                        console.log(`🚀 [OpenAI] Recommendation ${index + 1}:`, {
                            bookTitle: rec.book?.title,
                            bookAuthor: rec.book?.author,
                            bookGenre: rec.book?.genre,
                            score: rec.score,
                            reason: rec.reason,
                            source: rec.recommendation_source,
                            confidence: rec.ai_confidence
                        });
                    });
                }
                
                if (data.success && recommendationsArray.length > 0) {
                    console.log('🚀 [OpenAI] --- RENDERING RESULTS ---');
                    
                    // Clear previous results
                    grid.innerHTML = '';
                    console.log('🚀 [OpenAI] Previous results cleared');
                    
                    // Show results
                    resultsArea.classList.remove('hidden');
                    console.log('🚀 [OpenAI] Results area made visible');
                    
                    // Add recommendations to grid
                    recommendationsArray.forEach((rec, index) => {
                        console.log(`🚀 [OpenAI] Creating card ${index + 1} for:`, rec.book?.title);
                        const bookCard = createAIBookCard(rec);
                        grid.appendChild(bookCard);
                        console.log(`🚀 [OpenAI] Card ${index + 1} added to grid`);
                    });
                    
                    // Success state
                    buttonText.textContent = `✅ ${data.total_found} Empfehlungen gefunden!`;
                    console.log('🚀 [OpenAI] Success message displayed');
                    
                    // Scroll to results
                    setTimeout(() => {
                        resultsArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        console.log('🚀 [OpenAI] Scrolled to results area');
                    }, 500);
                    
                    console.log('✅ [OpenAI] === AI ANALYSIS COMPLETED SUCCESSFULLY ===');
                    
                } else {
                    console.warn('⚠️ [OpenAI] No recommendations received');
                    console.warn('⚠️ [OpenAI] Response data:', data);
                    buttonText.textContent = '😔 Keine Empfehlungen gefunden';
                }
                
            } catch (error) {
                console.error('❌ [OpenAI] === AI ANALYSIS FAILED ===');
                console.error('❌ [OpenAI] Error type:', error.constructor.name);
                console.error('❌ [OpenAI] Error message:', error.message);
                console.error('❌ [OpenAI] Error stack:', error.stack);
                console.error('❌ [OpenAI] Full error object:', error);
                
                buttonText.textContent = `❌ Fehler: ${error.message}`;
            } finally {
                console.log('🚀 [OpenAI] --- CLEANUP PHASE ---');
                
                // Reset button after 3 seconds
                setTimeout(() => {
                    btn.disabled = false;
                    spinner.classList.add('hidden');
                    buttonText.textContent = '🤖 Erneut analysieren';
                    console.log('🚀 [OpenAI] UI reset completed');
                    console.log('🚀 [OpenAI] === AI ANALYSIS SESSION ENDED ===');
                }, 3000);
            }
        }

        // Create book card for AI recommendations
        function createAIBookCard(rec) {
            const card = document.createElement('div');
            card.className = 'bg-black/40 backdrop-blur-sm border border-purple-400 rounded-xl p-6 hover:border-yellow-400 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/20';
            
            const imageUrl = rec.book.image_path 
                ? (rec.book.image_path.startsWith('images/') 
                    ? `/${rec.book.image_path}` 
                    : `/storage/${rec.book.image_path}`)
                : null;
            
            card.innerHTML = `
                <div class="flex flex-col h-full">
                    <!-- Book Cover -->
                    <div class="mb-4">
                        ${imageUrl 
                            ? `<img src="${imageUrl}" alt="${rec.book.title}" class="w-full h-48 object-cover rounded-lg shadow-lg">` 
                            : `<div class="w-full h-48 bg-gray-700 rounded-lg flex items-center justify-center">
                                 <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                     <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                 </svg>
                               </div>`
                        }
                    </div>
                    
                    <!-- Book Info -->
                    <div class="flex-1">
                        <h4 class="font-bold text-white text-lg mb-2">${rec.book.title}</h4>
                        <p class="text-gray-300 text-sm mb-3">von ${rec.book.author}</p>
                        
                        <!-- AI Info -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-1 bg-purple-600 text-white rounded-full text-xs font-bold">
                                    ${rec.book.genre}
                                </span>
                                <span class="text-yellow-400 text-sm font-bold">
                                    AI Score: ${rec.score}
                                </span>
                            </div>
                            
                            ${rec.book.ratings_count > 0 
                                ? `<div class="flex items-center mb-2">
                                     <span class="text-yellow-500 mr-1">⭐</span>
                                     <span class="text-sm text-gray-300">
                                         ${parseFloat(rec.book.ratings_avg_rating).toFixed(1)} (${rec.book.ratings_count} Reviews)
                                     </span>
                                   </div>`
                                : '<div class="text-gray-500 text-xs mb-2">Noch keine Bewertungen</div>'
                            }
                        </div>
                        
                        <!-- AI Reason -->
                        <div class="bg-purple-900/50 p-3 rounded-lg mb-4">
                            <div class="text-purple-300 text-xs font-bold mb-1">🤖 ${rec.recommendation_source}</div>
                            <p class="text-gray-200 text-sm">${rec.reason}</p>
                        </div>
                        
                                <!-- Actions -->
        <div class="space-y-2">
            <a href="/books/${rec.book.id}" 
               class="block w-full text-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all font-medium">
                📖 Details ansehen
            </a>
            ${rec.book.status === 'verfügbar' 
                ? (window.isAuthenticated 
                    ? `<form method="POST" action="/loans" class="inline w-full">
                         <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                         <input type="hidden" name="book_id" value="${rec.book.id}">
                         <button type="submit" 
                                 class="w-full px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all font-medium">
                             📚 Ausleihen
                         </button>
                       </form>`
                    : `<a href="/login" 
                         class="block w-full text-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white rounded-lg hover:from-yellow-700 hover:to-orange-700 transition-all font-medium">
                         🔐 Anmelden zum Ausleihen
                       </a>`
                  )
                : '<div class="w-full px-4 py-2 bg-gray-600 text-gray-300 rounded-lg text-center text-sm">Nicht verfügbar</div>'
            }
        </div>
                    </div>
                </div>
            `;
            
            return card;
        }

        // Function to update hero book when carousel book is clicked
        function updateHeroBook(bookData) {
            // Update hero title and info
            const heroTitle = document.querySelector('.hero-book-title');
            const heroAuthor = document.querySelector('.hero-book-author');
            const heroDescription = document.querySelector('.hero-book-description');
            const heroImage = document.querySelector('.hero-background');
            const loanLink = document.querySelector('.hero-loan-link');
            
            if (heroTitle) heroTitle.textContent = bookData.title;
            if (heroAuthor) heroAuthor.textContent = bookData.author;
            if (heroDescription) heroDescription.textContent = bookData.description;
            
            // Update background image
            if (heroImage && bookData.image) {
                const imageUrl = bookData.image.startsWith('images/') 
                    ? `/bookshare/${bookData.image}`
                    : `/bookshare/storage/${bookData.image}`;
                heroImage.style.backgroundImage = `url('${imageUrl}')`;
            }
            
            // Update loan link
            if (loanLink) {
                // Update link to point to book details page
                loanLink.href = `/bookshare/books/${bookData.id}`;
                
                // Update button visibility based on auth status and book availability
                const registerLink = document.querySelector('.hero-register-link');
                
                if (window.isAuthenticated) {
                    if (bookData.status === 'verfügbar' && bookData.owner_id != window.currentUserId) {
                        if (loanLink) loanLink.style.display = 'inline-block';
                        if (registerLink) registerLink.style.display = 'none';
                    } else {
                        if (loanLink) loanLink.style.display = 'none';
                        if (registerLink) registerLink.style.display = 'none';
                    }
                } else {
                    if (loanLink) loanLink.style.display = 'none';
                    if (registerLink) registerLink.style.display = 'inline-block';
                }
            }
        }
        
        // Attach event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const aiBtn = document.getElementById('aiAnalysisBtn');
            if (aiBtn) {
                aiBtn.addEventListener('click', performAIAnalysis);
            }
            
            // Add click listeners to book cards
            document.querySelectorAll('.book-selector').forEach(card => {
                card.addEventListener('click', function() {
                    const bookId = this.dataset.bookId;
                    // Navigate to book detail page
                    window.location.href = `/books/${bookId}`;
                });
            });
        });

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

    @auth
    <!-- Right Sidebar - User Profile (only for authenticated users) -->
    <div class="user-sidebar fixed top-0 right-0 h-full w-84 shadow-2xl z-50">
        <!-- Sidebar Content -->
        <div class="h-full flex flex-col">
            <!-- Header -->
            <div class="p-6 border-b border-slate-700/30 bg-gradient-to-r from-slate-700 to-slate-800">
                <div class="flex items-center space-x-4">
                    <div class="profile-avatar">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="h-16 w-16 rounded-full object-cover border-4 border-white shadow-lg" />
                        @else
                            <div class="h-16 w-16 rounded-full bg-slate-600 flex items-center justify-center text-slate-200 font-bold text-xl border-4 border-slate-500 shadow-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-slate-100">
                        <h3 class="text-lg font-semibold">{{ Auth::user()->name }}</h3>
                        <p class="text-slate-300 text-sm">{{ Auth::user()->email }}</p>
                        <div class="flex items-center mt-1">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse mr-2"></div>
                            <span class="text-xs text-slate-300">Online</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Stats -->
            <div class="p-4 bg-slate-800/50 border-b border-slate-700/30">
                <div class="grid grid-cols-3 gap-4 text-center">
                    @php
                        $userBooksCount = \App\Models\Book::where('owner_id', Auth::id())->count();
                        $userLoansCount = \App\Models\Loan::where('borrower_id', Auth::id())->count();
                        $userRatingsCount = \App\Models\Rating::where('user_id', Auth::id())->count();
                    @endphp
                    <div class="bg-slate-700/60 rounded-lg p-3 shadow-sm border border-slate-600/30">
                        <div class="text-2xl font-bold text-blue-400">{{ $userBooksCount }}</div>
                        <div class="text-xs text-slate-300">Bücher</div>
                    </div>
                    <div class="bg-slate-700/60 rounded-lg p-3 shadow-sm border border-slate-600/30">
                        <div class="text-2xl font-bold text-emerald-400">{{ $userLoansCount }}</div>
                        <div class="text-xs text-slate-300">Ausleihen</div>
                    </div>
                    <div class="bg-slate-700/60 rounded-lg p-3 shadow-sm border border-slate-600/30">
                        <div class="text-2xl font-bold text-amber-400">{{ $userRatingsCount }}</div>
                        <div class="text-xs text-slate-300">Bewertungen</div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="flex-1 p-4">
                <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wide mb-4">Navigation</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Meine Bücher</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('loans.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span>Ausleihen</span>
                            @php
                                $totalLoansCount = \App\Models\Loan::where('lender_id', Auth::id())
                                    ->where('status', \App\Models\Loan::STATUS_ANGEFRAGT)
                                    ->count() + \App\Models\Loan::where('borrower_id', Auth::id())
                                    ->whereIn('status', [\App\Models\Loan::STATUS_ANGEFRAGT, \App\Models\Loan::STATUS_AKTIV])
                                    ->count();
                            @endphp
                            @if($totalLoansCount > 0)
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full ml-auto">{{ $totalLoansCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ratings.user', auth()->user()) }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <span>Bewertungen</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profil bearbeiten</span>
                        </a>
                    </li>
                </ul>

                <!-- Settings Section -->
                <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wide mb-4 mt-8">Einstellungen</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Allgemein</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-all duration-200 group w-full text-left">
                                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Abmelden</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Footer -->
           <!--  <div class="p-4 border-t border-slate-700/30">
                <div class="text-center text-xs text-slate-400">
                    <p>&copy; {{ date('Y') }} BookShare</p>
                    <p class="mt-1">Made with ❤️</p>
                </div>
            </div> -->
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.user-sidebar');
            const userProfileTrigger = document.querySelector('.sidebar-user-trigger');
            
            // Function to show sidebar
            function showSidebar() {
                if (sidebar) {
                    sidebar.classList.add('show');
                }
            }
            
            // Function to hide sidebar
            function hideSidebar() {
                if (sidebar) {
                    setTimeout(() => {
                        if (!sidebar.matches(':hover') && 
                            !userProfileTrigger?.matches(':hover')) {
                            sidebar.classList.remove('show');
                        }
                    }, 100);
                }
            }
            
            // Show sidebar on user profile trigger hover (only for navigation in welcome page)
            if (userProfileTrigger) {
                userProfileTrigger.addEventListener('mouseenter', showSidebar);
                userProfileTrigger.addEventListener('mouseleave', hideSidebar);
            }
            
            // Keep sidebar open when hovering over it
            if (sidebar) {
                sidebar.addEventListener('mouseenter', showSidebar);
                sidebar.addEventListener('mouseleave', hideSidebar);
            }
        });
    </script>
    @endauth
</body>
</html>
