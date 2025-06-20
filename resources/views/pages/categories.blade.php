<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alle Kategorien - BookShare</title>
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
    <section class="relative py-20 bg-gradient-to-br from-emerald-900 via-teal-900 to-cyan-900">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Alle Kategorien</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Entdecke Bücher aus allen Bereichen und finde dein nächstes Lieblingsbuch.
            </p>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-6xl mx-auto px-6">
            
            <!-- Category Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Fantasy & Science Fiction -->
                <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">🧙‍♂️</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Fantasy & Science Fiction</h3>
                        <p class="text-purple-100 mb-6">
                            Tauche ein in magische Welten und futuristische Abenteuer
                        </p>
                        <div class="text-sm text-purple-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">Fantasy</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Sci-Fi</span>
                        </div>
                    </div>
                </div>

                <!-- Klassiker -->
                <div class="bg-gradient-to-br from-amber-600 to-orange-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">📜</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Klassiker</h3>
                        <p class="text-amber-100 mb-6">
                            Zeitlose Meisterwerke der Weltliteratur
                        </p>
                        <div class="text-sm text-amber-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">Literatur</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Klassiker</span>
                        </div>
                    </div>
                </div>

                <!-- Thriller & Krimi -->
                <div class="bg-gradient-to-br from-red-600 to-rose-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Thriller & Krimi</h3>
                        <p class="text-red-100 mb-6">
                            Spannende Geschichten voller Rätsel und Nervenkitzel
                        </p>
                        <div class="text-sm text-red-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">Thriller</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Krimi</span>
                        </div>
                    </div>
                </div>

                <!-- Sachbuch -->
                <div class="bg-gradient-to-br from-blue-600 to-cyan-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">🎓</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Sachbuch</h3>
                        <p class="text-blue-100 mb-6">
                            Wissen, Bildung und praktische Ratgeber
                        </p>
                        <div class="text-sm text-blue-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">Bildung</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Ratgeber</span>
                        </div>
                    </div>
                </div>

                <!-- Biografie -->
                <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">👤</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Biografie</h3>
                        <p class="text-green-100 mb-6">
                            Lebensgeschichten faszinierender Persönlichkeiten
                        </p>
                        <div class="text-sm text-green-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">Biografie</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Autobiografie</span>
                        </div>
                    </div>
                </div>

                <!-- Roman -->
                <div class="bg-gradient-to-br from-pink-600 to-rose-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">💕</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Roman</h3>
                        <p class="text-pink-100 mb-6">
                            Emotionale Geschichten und zeitgenössische Literatur
                        </p>
                        <div class="text-sm text-pink-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">Roman</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Drama</span>
                        </div>
                    </div>
                </div>

                <!-- Humor -->
                <div class="bg-gradient-to-br from-yellow-600 to-orange-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">😄</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Humor</h3>
                        <p class="text-yellow-100 mb-6">
                            Bücher zum Lachen und für gute Laune
                        </p>
                        <div class="text-sm text-yellow-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">Humor</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Komödie</span>
                        </div>
                    </div>
                </div>

                <!-- Kochen -->
                <div class="bg-gradient-to-br from-teal-600 to-green-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">👨‍🍳</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Kochen</h3>
                        <p class="text-teal-100 mb-6">
                            Kochbücher und kulinarische Inspirationen
                        </p>
                        <div class="text-sm text-teal-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">Kochbuch</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Rezepte</span>
                        </div>
                    </div>
                </div>

                <!-- Technik -->
                <div class="bg-gradient-to-br from-slate-600 to-gray-700 rounded-xl p-8 hover:scale-105 transition-transform cursor-pointer">
                    <div class="text-center">
                        <div class="text-6xl mb-4">💻</div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Technik</h3>
                        <p class="text-slate-100 mb-6">
                            Programmierung, IT und technische Handbücher
                        </p>
                        <div class="text-sm text-slate-200">
                            <span class="bg-white/20 px-3 py-1 rounded-full mr-2">IT</span>
                            <span class="bg-white/20 px-3 py-1 rounded-full">Programmierung</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Popular Books by Category -->
            <div class="mt-20">
                <h2 class="text-3xl font-bold mb-8 text-white">Beliebte Bücher nach Kategorie</h2>
                
                <!-- Category Tabs -->
                <div class="flex flex-wrap gap-4 mb-8">
                    <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                        Fantasy
                    </button>
                    <button class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                        Klassiker
                    </button>
                    <button class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                        Thriller
                    </button>
                    <button class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                        Sachbuch
                    </button>
                </div>

                <!-- Books Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @for ($i = 1; $i <= 6; $i++)
                        <div class="group cursor-pointer">
                            <div class="aspect-[3/4] bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                                <div class="text-center text-white">
                                    <div class="text-2xl mb-2">📖</div>
                                    <div class="text-xs">Buch {{ $i }}</div>
                                </div>
                            </div>
                            <h3 class="text-sm font-semibold text-white mb-1">Beispiel Titel {{ $i }}</h3>
                            <p class="text-xs text-gray-400">Autor Name</p>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-20 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8 text-center">
                <h3 class="text-2xl font-bold mb-4 text-white">Entdecke dein nächstes Lieblingsbuch</h3>
                <p class="text-indigo-100 mb-6">
                    Stöbere durch unsere vielfältige Sammlung oder füge deine eigenen Bücher hinzu.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Kostenlos registrieren
                        </a>
                        <a href="{{ route('welcome') }}" class="px-6 py-3 bg-indigo-800 text-white rounded-lg font-semibold hover:bg-indigo-900 transition-colors">
                            Bücher durchsuchen
                        </a>
                    @else
                        <a href="{{ route('books.create') }}" class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Buch hinzufügen
                        </a>
                        <a href="{{ route('books.index') }}" class="px-6 py-3 bg-indigo-800 text-white rounded-lg font-semibold hover:bg-indigo-900 transition-colors">
                            Meine Bücher
                        </a>
                    @endguest
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html> 