<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Über uns - BookShare</title>
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
    <section class="relative py-20 bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Über BookShare</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Die Community-Plattform, die Buchliebhaber zusammenbringt und das Teilen von Wissen fördert.
            </p>
        </div>
    </section>

    <!-- Content -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-4xl mx-auto px-6">
            
            <!-- Mission -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Unsere Mission</h2>
                <div class="bg-gray-800 rounded-xl p-8 shadow-lg">
                    <p class="text-lg text-gray-300 leading-relaxed mb-6">
                        BookShare wurde mit der Vision gegründet, eine Brücke zwischen Buchliebhabern zu schaffen. 
                        Wir glauben, dass Bücher nicht nur gelesen, sondern geteilt werden sollten - denn jedes Buch 
                        hat das Potenzial, mehrere Leben zu bereichern.
                    </p>
                    <p class="text-lg text-gray-300 leading-relaxed">
                        Unsere Plattform ermöglicht es Menschen, ihre persönlichen Bibliotheken zu öffnen und 
                        gleichzeitig Zugang zu einer Vielzahl von Büchern in ihrer Gemeinschaft zu erhalten.
                    </p>
                </div>
            </div>

            <!-- Values -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Unsere Werte</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-400">Gemeinschaft</h3>
                        <p class="text-gray-300">
                            Wir fördern den Aufbau starker, lokaler Gemeinschaften durch das gemeinsame Interesse am Lesen.
                        </p>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-400">Nachhaltigkeit</h3>
                        <p class="text-gray-300">
                            Das Teilen von Büchern reduziert Verschwendung und macht Literatur für alle zugänglicher.
                        </p>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-400">Vertrauen</h3>
                        <p class="text-gray-300">
                            Unsere Plattform basiert auf gegenseitigem Vertrauen und Respekt zwischen den Nutzern.
                        </p>
                    </div>
                    <div class="bg-gray-800 rounded-xl p-6 shadow-lg">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-400">Bildung</h3>
                        <p class="text-gray-300">
                            Wir unterstützen lebenslanges Lernen durch den einfachen Zugang zu vielfältiger Literatur.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Story -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Unsere Geschichte</h2>
                <div class="bg-gray-800 rounded-xl p-8 shadow-lg">
                    <p class="text-lg text-gray-300 leading-relaxed mb-6">
                        BookShare entstand aus der einfachen Idee, dass zu viele wertvolle Bücher ungelesen in 
                        Regalen stehen, während andere Menschen genau nach diesen Büchern suchen. Was als kleines 
                        Nachbarschaftsprojekt begann, hat sich zu einer Plattform entwickelt, die Buchliebhaber 
                        weltweit verbindet.
                    </p>
                    <p class="text-lg text-gray-300 leading-relaxed">
                        Heute sind wir stolz darauf, eine lebendige Community zu unterstützen, in der Wissen 
                        geteilt, neue Freundschaften geschlossen und die Liebe zum Lesen gefördert wird.
                    </p>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center">
                <h2 class="text-3xl font-bold mb-6 text-white">Werde Teil unserer Community</h2>
                <p class="text-xl text-gray-300 mb-8">
                    Entdecke neue Bücher und teile deine Lieblingslektüre mit anderen.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-semibold text-lg transition-colors inline-block">
                        Jetzt kostenlos registrieren
                    </a>
                @else
                    <a href="{{ route('books.index') }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 rounded-lg font-semibold text-lg transition-colors inline-block">
                        Meine Bücher verwalten
                    </a>
                @endguest
            </div>

        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html> 