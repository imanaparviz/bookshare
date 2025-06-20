<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beliebte Bücher - BookShare</title>
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
    <section class="relative py-20 bg-gradient-to-br from-red-900 via-pink-900 to-rose-900">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Beliebte Bücher</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Die meistgeliehenen und bestbewerteten Bücher unserer Community.
            </p>
        </div>
    </section>

    <!-- Popular Books -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-6xl mx-auto px-6">
            
            <!-- Top 3 -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Top 3 der Woche</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- #1 -->
                    <div class="bg-gradient-to-br from-yellow-600 to-orange-700 rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-4 right-4 bg-white text-yellow-600 rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl">
                            1
                        </div>
                        <div class="text-center">
                            <div class="w-32 h-40 mx-auto mb-4 bg-white/20 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('images/01_herr_der_ringe_die_gefaehrten.jpg') }}" alt="Der Herr der Ringe" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Der Herr der Ringe</h3>
                            <p class="text-yellow-100 mb-4">J.R.R. Tolkien</p>
                            <div class="flex items-center justify-center space-x-2 mb-4">
                                <div class="flex text-yellow-300">
                                    ⭐⭐⭐⭐⭐
                                </div>
                                <span class="text-yellow-100 text-sm">(4.9)</span>
                            </div>
                            <p class="text-yellow-100 text-sm">47 mal ausgeliehen</p>
                        </div>
                    </div>

                    <!-- #2 -->
                    <div class="bg-gradient-to-br from-gray-600 to-slate-700 rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-4 right-4 bg-white text-gray-600 rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl">
                            2
                        </div>
                        <div class="text-center">
                            <div class="w-32 h-40 mx-auto mb-4 bg-white/20 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('images/03_harry_potter_stein_der_weisen.jpg') }}" alt="Harry Potter" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Harry Potter</h3>
                            <p class="text-gray-100 mb-4">J.K. Rowling</p>
                            <div class="flex items-center justify-center space-x-2 mb-4">
                                <div class="flex text-yellow-300">
                                    ⭐⭐⭐⭐⭐
                                </div>
                                <span class="text-gray-100 text-sm">(4.8)</span>
                            </div>
                            <p class="text-gray-100 text-sm">42 mal ausgeliehen</p>
                        </div>
                    </div>

                    <!-- #3 -->
                    <div class="bg-gradient-to-br from-amber-700 to-yellow-800 rounded-xl p-6 relative overflow-hidden">
                        <div class="absolute top-4 right-4 bg-white text-amber-700 rounded-full w-12 h-12 flex items-center justify-center font-bold text-xl">
                            3
                        </div>
                        <div class="text-center">
                            <div class="w-32 h-40 mx-auto mb-4 bg-white/20 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('images/02_dune_der_wuestenplanet.jpg') }}" alt="Dune" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Dune</h3>
                            <p class="text-amber-100 mb-4">Frank Herbert</p>
                            <div class="flex items-center justify-center space-x-2 mb-4">
                                <div class="flex text-yellow-300">
                                    ⭐⭐⭐⭐⭐
                                </div>
                                <span class="text-amber-100 text-sm">(4.7)</span>
                            </div>
                            <p class="text-amber-100 text-sm">38 mal ausgeliehen</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Popular Books List -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Alle beliebten Bücher</h2>
                
                <div class="space-y-4">
                    @php
                        $popularBooks = [
                            ['rank' => 4, 'title' => '1984', 'author' => 'George Orwell', 'image' => '06_1984_george_orwell.jpg', 'rating' => 4.6, 'loans' => 35],
                            ['rank' => 5, 'title' => 'Foundation', 'author' => 'Isaac Asimov', 'image' => '04_foundation_der_psychohistoriker.jpg', 'rating' => 4.5, 'loans' => 32],
                            ['rank' => 6, 'title' => 'Game of Thrones', 'author' => 'George R.R. Martin', 'image' => '05_das_lied_von_eis_und_feuer.jpg', 'rating' => 4.4, 'loans' => 29],
                            ['rank' => 7, 'title' => 'Stolz und Vorurteil', 'author' => 'Jane Austen', 'image' => '07_stolz_und_vorurteil.jpg', 'rating' => 4.3, 'loans' => 26],
                            ['rank' => 8, 'title' => 'Der große Gatsby', 'author' => 'F. Scott Fitzgerald', 'image' => '09_der_grosse_gatsby.jpg', 'rating' => 4.2, 'loans' => 24],
                            ['rank' => 9, 'title' => 'Clean Code', 'author' => 'Robert C. Martin', 'image' => '13_clean_code_robert_martin.jpg', 'rating' => 4.1, 'loans' => 22],
                            ['rank' => 10, 'title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'image' => '14_sapiens_yuval_harari.jpg', 'rating' => 4.0, 'loans' => 20],
                        ];
                    @endphp

                    @foreach($popularBooks as $book)
                        <div class="bg-gray-800 rounded-xl p-6 flex items-center space-x-6 hover:bg-gray-700 transition-colors">
                            <div class="flex items-center justify-center w-12 h-12 bg-indigo-600 rounded-full font-bold text-xl">
                                {{ $book['rank'] }}
                            </div>
                            <div class="w-16 h-20 flex-shrink-0">
                                @if(file_exists(public_path('images/' . $book['image'])))
                                    <img src="{{ asset('images/' . $book['image']) }}" alt="{{ $book['title'] }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <div class="text-white text-xs">📖</div>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-white mb-1">{{ $book['title'] }}</h3>
                                <p class="text-gray-400 text-sm mb-2">{{ $book['author'] }}</p>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-1">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($book['rating']))
                                                    ⭐
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-gray-400 text-sm">({{ $book['rating'] }})</span>
                                    </div>
                                    <span class="text-gray-400 text-sm">{{ $book['loans'] }} mal ausgeliehen</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-sm font-semibold transition-colors">
                                    Details
                                </button>
                                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-semibold transition-colors">
                                    Ausleihen
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold mb-6 text-white">Community Statistiken</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">2,847</div>
                            <div class="text-indigo-100 text-sm">Bücher geteilt</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">1,293</div>
                            <div class="text-indigo-100 text-sm">Aktive Leser</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">5,621</div>
                            <div class="text-indigo-100 text-sm">Ausleihen</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">4.6</div>
                            <div class="text-indigo-100 text-sm">⭐ Durchschnitt</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html> 