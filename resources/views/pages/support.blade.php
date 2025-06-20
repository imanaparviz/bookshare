<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Support - BookShare</title>
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
    <section class="relative py-20 bg-gradient-to-br from-teal-900 via-cyan-900 to-blue-900">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Support Center</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Wir helfen dir bei allen Fragen rund um BookShare. Schnell, freundlich und kompetent.
            </p>
        </div>
    </section>

    <!-- Support Options -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-6xl mx-auto px-6">
            
            <!-- Quick Support -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Sofortige Hilfe</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Live Chat -->
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl p-8 text-center hover:scale-105 transition-transform cursor-pointer">
                        <div class="text-6xl mb-4">💬</div>
                        <h3 class="text-2xl font-bold text-white mb-4">Live-Chat</h3>
                        <p class="text-green-100 mb-6">
                            Sofortige Hilfe von unserem Support-Team
                        </p>
                        <div class="text-sm text-green-200 mb-4">
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                <span>Online</span>
                            </div>
                            <div>Mo-Fr: 9:00-18:00 Uhr</div>
                        </div>
                        <button class="px-6 py-3 bg-white text-green-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Chat starten
                        </button>
                    </div>

                    <!-- Email Support -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl p-8 text-center hover:scale-105 transition-transform cursor-pointer">
                        <div class="text-6xl mb-4">📧</div>
                        <h3 class="text-2xl font-bold text-white mb-4">E-Mail Support</h3>
                        <p class="text-blue-100 mb-6">
                            Detaillierte Hilfe per E-Mail
                        </p>
                        <div class="text-sm text-blue-200 mb-4">
                            <div>support@bookshare.com</div>
                            <div>Antwort binnen 24h</div>
                        </div>
                        <button class="px-6 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            E-Mail senden
                        </button>
                    </div>

                    <!-- Help Center -->
                    <div class="bg-gradient-to-br from-purple-600 to-pink-700 rounded-xl p-8 text-center hover:scale-105 transition-transform cursor-pointer">
                        <div class="text-6xl mb-4">📚</div>
                        <h3 class="text-2xl font-bold text-white mb-4">Hilfe-Center</h3>
                        <p class="text-purple-100 mb-6">
                            Selbsthilfe mit FAQ und Anleitungen
                        </p>
                        <div class="text-sm text-purple-200 mb-4">
                            <div>50+ Artikel</div>
                            <div>Schritt-für-Schritt Anleitungen</div>
                        </div>
                        <a href="{{ route('help') }}" class="inline-block px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Hilfe durchsuchen
                        </a>
                    </div>

                </div>
            </div>

            <!-- Support Categories -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Häufige Support-Themen</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-700 transition-colors cursor-pointer">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-2">Konto & Profil</h3>
                                <p class="text-gray-400 text-sm mb-3">
                                    Hilfe bei Anmeldung, Passwort zurücksetzen, Profil bearbeiten
                                </p>
                                <div class="text-indigo-400 text-sm font-medium">15 Artikel verfügbar →</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-700 transition-colors cursor-pointer">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-2">Bücher verwalten</h3>
                                <p class="text-gray-400 text-sm mb-3">
                                    Bücher hinzufügen, bearbeiten, löschen und organisieren
                                </p>
                                <div class="text-indigo-400 text-sm font-medium">12 Artikel verfügbar →</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-700 transition-colors cursor-pointer">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-2">Ausleihen & Verleihen</h3>
                                <p class="text-gray-400 text-sm mb-3">
                                    Bücher ausleihen, Anfragen bearbeiten, Rückgabe verwalten
                                </p>
                                <div class="text-indigo-400 text-sm font-medium">18 Artikel verfügbar →</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-6 hover:bg-gray-700 transition-colors cursor-pointer">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-2">Technische Probleme</h3>
                                <p class="text-gray-400 text-sm mb-3">
                                    App-Fehler, Ladeprobleme, Browser-Kompatibilität
                                </p>
                                <div class="text-indigo-400 text-sm font-medium">8 Artikel verfügbar →</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Support Status -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Service Status</h2>
                <div class="bg-gray-800 rounded-xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-white">Alle Systeme funktionieren normal</h3>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            <span class="text-green-400 font-medium">Operational</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                <span class="text-white font-medium">Website</span>
                            </div>
                            <div class="text-sm text-gray-400">99.9% Uptime</div>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                <span class="text-white font-medium">API</span>
                            </div>
                            <div class="text-sm text-gray-400">99.8% Uptime</div>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                <span class="text-white font-medium">Database</span>
                            </div>
                            <div class="text-sm text-gray-400">100% Uptime</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold mb-4 text-white">Brauchst du persönliche Hilfe?</h3>
                    <p class="text-indigo-100 mb-6">
                        Unser Support-Team ist da, um dir bei allen Fragen zu helfen. 
                        Kontaktiere uns über deinen bevorzugten Kanal.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Live-Chat starten
                        </button>
                        <a href="{{ route('contact') }}" class="px-6 py-3 bg-indigo-800 text-white rounded-lg font-semibold hover:bg-indigo-900 transition-colors">
                            Kontaktformular
                        </a>
                        <a href="mailto:support@bookshare.com" class="px-6 py-3 bg-indigo-700 text-white rounded-lg font-semibold hover:bg-indigo-800 transition-colors">
                            E-Mail senden
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html> 