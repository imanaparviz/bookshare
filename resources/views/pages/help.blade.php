<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hilfe - BookShare</title>
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
    <section class="relative py-20 bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Hilfe & Support</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Finde Antworten auf häufige Fragen oder kontaktiere unser Support-Team.
            </p>
        </div>
    </section>

    <!-- Quick Help -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-6xl mx-auto px-6">
            
            <!-- Search -->
            <div class="mb-12">
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input type="text" placeholder="Wonach suchst du Hilfe?" 
                               class="w-full px-6 py-4 bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                <div class="bg-gray-800 rounded-xl p-6 text-center hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="text-4xl mb-4">🚀</div>
                    <h3 class="text-xl font-bold mb-2 text-white">Erste Schritte</h3>
                    <p class="text-gray-300 text-sm">Lerne die Grundlagen von BookShare kennen</p>
                </div>
                <div class="bg-gray-800 rounded-xl p-6 text-center hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="text-4xl mb-4">📚</div>
                    <h3 class="text-xl font-bold mb-2 text-white">Bücher verwalten</h3>
                    <p class="text-gray-300 text-sm">Hinzufügen, bearbeiten und teilen</p>
                </div>
                <div class="bg-gray-800 rounded-xl p-6 text-center hover:bg-gray-700 transition-colors cursor-pointer">
                    <div class="text-4xl mb-4">🤝</div>
                    <h3 class="text-xl font-bold mb-2 text-white">Ausleihen</h3>
                    <p class="text-gray-300 text-sm">So funktioniert das Leihen und Verleihen</p>
                </div>
            </div>

            <!-- FAQ -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold mb-8 text-white">Häufig gestellte Fragen</h2>
                
                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="bg-gray-800 rounded-xl p-6">
                        <button class="w-full text-left flex justify-between items-center" onclick="toggleFaq(1)">
                            <h3 class="text-xl font-semibold text-white">Wie füge ich ein neues Buch hinzu?</h3>
                            <svg id="faq-arrow-1" class="w-6 h-6 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="faq-content-1" class="hidden mt-4 text-gray-300">
                            <p class="mb-4">Um ein neues Buch hinzuzufügen, gehe folgendermaßen vor:</p>
                            <ol class="list-decimal list-inside space-y-2">
                                <li>Melde dich in deinem BookShare-Konto an</li>
                                <li>Klicke auf "Meine Bücher" in der Navigation</li>
                                <li>Wähle "Neues Buch hinzufügen"</li>
                                <li>Fülle alle erforderlichen Informationen aus (Titel, Autor, ISBN, etc.)</li>
                                <li>Lade optional ein Foto des Buchcovers hoch</li>
                                <li>Klicke auf "Buch speichern"</li>
                            </ol>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="bg-gray-800 rounded-xl p-6">
                        <button class="w-full text-left flex justify-between items-center" onclick="toggleFaq(2)">
                            <h3 class="text-xl font-semibold text-white">Wie kann ich ein Buch ausleihen?</h3>
                            <svg id="faq-arrow-2" class="w-6 h-6 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="faq-content-2" class="hidden mt-4 text-gray-300">
                            <p class="mb-4">Das Ausleihen eines Buches ist einfach:</p>
                            <ol class="list-decimal list-inside space-y-2">
                                <li>Suche das gewünschte Buch über die Suchfunktion</li>
                                <li>Klicke auf das Buch, um die Details anzuzeigen</li>
                                <li>Prüfe die Verfügbarkeit und die Bedingungen des Besitzers</li>
                                <li>Klicke auf "Ausleihen anfragen"</li>
                                <li>Warte auf die Bestätigung des Buchbesitzers</li>
                                <li>Vereinbare Übergabe und Rückgabe direkt mit dem Besitzer</li>
                            </ol>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="bg-gray-800 rounded-xl p-6">
                        <button class="w-full text-left flex justify-between items-center" onclick="toggleFaq(3)">
                            <h3 class="text-xl font-semibold text-white">Ist BookShare kostenlos?</h3>
                            <svg id="faq-arrow-3" class="w-6 h-6 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="faq-content-3" class="hidden mt-4 text-gray-300">
                            <p>Ja, BookShare ist komplett kostenlos! Du kannst:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Unbegrenzt Bücher hinzufügen und verwalten</li>
                                <li>Bücher von anderen Nutzern ausleihen</li>
                                <li>Teil der Community werden</li>
                                <li>Alle Grundfunktionen nutzen</li>
                            </ul>
                            <p class="mt-4">Unser Ziel ist es, das Teilen von Büchern für alle zugänglich zu machen.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="bg-gray-800 rounded-xl p-6">
                        <button class="w-full text-left flex justify-between items-center" onclick="toggleFaq(4)">
                            <h3 class="text-xl font-semibold text-white">Was passiert, wenn ein Buch beschädigt wird?</h3>
                            <svg id="faq-arrow-4" class="w-6 h-6 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="faq-content-4" class="hidden mt-4 text-gray-300">
                            <p class="mb-4">Bei Schäden an geliehenen Büchern gilt folgendes Vorgehen:</p>
                            <ol class="list-decimal list-inside space-y-2">
                                <li>Informiere den Buchbesitzer sofort über den Schaden</li>
                                <li>Dokumentiere den Schaden mit Fotos</li>
                                <li>Versuche eine einvernehmliche Lösung zu finden</li>
                                <li>Bei größeren Schäden kann ein Ersatz oder eine Entschädigung vereinbart werden</li>
                                <li>Kontaktiere bei Problemen unser Support-Team</li>
                            </ol>
                            <p class="mt-4">Wir empfehlen, vor der Ausleihe den Zustand des Buches zu dokumentieren.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="bg-gray-800 rounded-xl p-6">
                        <button class="w-full text-left flex justify-between items-center" onclick="toggleFaq(5)">
                            <h3 class="text-xl font-semibold text-white">Wie kann ich mein Profil bearbeiten?</h3>
                            <svg id="faq-arrow-5" class="w-6 h-6 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="faq-content-5" class="hidden mt-4 text-gray-300">
                            <p class="mb-4">So bearbeitest du dein Profil:</p>
                            <ol class="list-decimal list-inside space-y-2">
                                <li>Klicke oben rechts auf dein Profilbild oder deinen Namen</li>
                                <li>Wähle "Profil bearbeiten" aus dem Dropdown-Menü</li>
                                <li>Ändere die gewünschten Informationen (Name, E-Mail, Beschreibung, etc.)</li>
                                <li>Lade optional ein neues Profilbild hoch</li>
                                <li>Klicke auf "Änderungen speichern"</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold mb-4 text-white">Weitere Hilfe benötigt?</h3>
                    <p class="text-indigo-100 mb-6">
                        Unser Support-Team hilft dir gerne weiter. Kontaktiere uns per E-Mail oder über unser Kontaktformular.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="mailto:support@bookshare.com" class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            E-Mail senden
                        </a>
                        <a href="{{ route('contact') }}" class="px-6 py-3 bg-indigo-800 text-white rounded-lg font-semibold hover:bg-indigo-900 transition-colors">
                            Kontaktformular
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')

    <script>
        function toggleFaq(id) {
            const content = document.getElementById(`faq-content-${id}`);
            const arrow = document.getElementById(`faq-arrow-${id}`);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }
    </script>
</body>
</html> 