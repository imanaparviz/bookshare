<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kontakt - BookShare</title>
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
    <section class="relative py-20 bg-gradient-to-br from-cyan-900 via-blue-900 to-indigo-900">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Kontakt</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Hast du Fragen, Anregungen oder benötigst Hilfe? Wir sind für dich da!
            </p>
        </div>
    </section>

    <!-- Contact -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-6xl mx-auto px-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-bold mb-8 text-white">Schreib uns eine Nachricht</h2>
                    
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Vorname</label>
                                <input type="text" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Dein Vorname">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-2">Nachname</label>
                                <input type="text" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Dein Nachname">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">E-Mail-Adresse</label>
                            <input type="email" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="deine@email.com">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Betreff</label>
                            <select class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option>Allgemeine Anfrage</option>
                                <option>Technischer Support</option>
                                <option>Feedback</option>
                                <option>Partnerschaft</option>
                                <option>Presse</option>
                                <option>Sonstiges</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Nachricht</label>
                            <textarea rows="6" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none" placeholder="Teile uns deine Nachricht mit..."></textarea>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="privacy" class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-700 rounded focus:ring-indigo-500">
                            <label for="privacy" class="ml-2 text-sm text-gray-300">
                                Ich stimme der <a href="#" class="text-indigo-400 hover:text-indigo-300">Datenschutzerklärung</a> zu.
                            </label>
                        </div>
                        
                        <button type="submit" class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-semibold text-white transition-colors">
                            Nachricht senden
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div>
                    <h2 class="text-3xl font-bold mb-8 text-white">Weitere Kontaktmöglichkeiten</h2>
                    
                    <div class="space-y-8">
                        
                        <!-- Email -->
                        <div class="bg-gray-800 rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">E-Mail</h3>
                                    <p class="text-gray-400">support@bookshare.com</p>
                                    <p class="text-sm text-gray-500">Antwort innerhalb von 24 Stunden</p>
                                </div>
                            </div>
                        </div>

                        <!-- Support -->
                        <div class="bg-gray-800 rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Live-Chat</h3>
                                    <p class="text-gray-400">Direkter Support</p>
                                    <p class="text-sm text-gray-500">Mo-Fr: 9:00-18:00 Uhr</p>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ -->
                        <div class="bg-gray-800 rounded-xl p-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Hilfe-Center</h3>
                                    <p class="text-gray-400">Häufige Fragen & Antworten</p>
                                    <a href="{{ route('help') }}" class="text-sm text-indigo-400 hover:text-indigo-300">Zum Hilfe-Center →</a>
                                </div>
                            </div>
                        </div>

                        <!-- Social -->
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Folge uns</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-blue-800 rounded-lg flex items-center justify-center hover:bg-blue-900 transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-pink-600 rounded-lg flex items-center justify-center hover:bg-pink-700 transition-colors">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z.017 0z"/>
                                    </svg>
                                </a>
                            </div>
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