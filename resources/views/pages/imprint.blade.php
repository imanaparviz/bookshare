<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Impressum - BookShare</title>
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
    <section class="relative py-20 bg-gradient-to-br from-gray-800 via-slate-800 to-zinc-800">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Impressum</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Rechtliche Informationen und Angaben gemäß § 5 TMG.
            </p>
        </div>
    </section>

    <!-- Imprint Content -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-4xl mx-auto px-6">
            
            <!-- Company Information -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-white mb-8">Anbieter</h2>
                <div class="bg-gray-800 rounded-xl p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-4">Firmeninformationen</h3>
                            <div class="space-y-2 text-gray-300">
                                <p><strong class="text-white">Firmenname:</strong> BookShare GmbH</p>
                                <p><strong class="text-white">Rechtsform:</strong> Gesellschaft mit beschränkter Haftung</p>
                                <p><strong class="text-white">Handelsregister:</strong> HRB 123456</p>
                                <p><strong class="text-white">Registergericht:</strong> Amtsgericht Berlin</p>
                                <p><strong class="text-white">USt-IdNr.:</strong> DE123456789</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-4">Anschrift</h3>
                            <div class="space-y-2 text-gray-300">
                                <p>BookShare GmbH</p>
                                <p>Musterstraße 123</p>
                                <p>10115 Berlin</p>
                                <p>Deutschland</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-white mb-8">Kontakt</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Allgemeine Kontaktdaten</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-gray-300">+49 (0) 30 123 456 78</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-300">info@bookshare.com</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-300">Mo-Fr: 9:00-18:00 Uhr</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Spezielle Kontakte</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-white font-medium">Support</p>
                                <p class="text-gray-300 text-sm">support@bookshare.com</p>
                            </div>
                            <div>
                                <p class="text-white font-medium">Datenschutz</p>
                                <p class="text-gray-300 text-sm">privacy@bookshare.com</p>
                            </div>
                            <div>
                                <p class="text-white font-medium">Presse</p>
                                <p class="text-gray-300 text-sm">press@bookshare.com</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Management -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-white mb-8">Geschäftsführung</h2>
                <div class="bg-gray-800 rounded-xl p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">MS</span>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2">Max Mustermann</h3>
                            <p class="text-gray-400 mb-2">Geschäftsführer & Gründer</p>
                            <p class="text-gray-300 text-sm">
                                Verantwortlich für Strategie und Produktentwicklung
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-teal-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">AM</span>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2">Anna Musterfrau</h3>
                            <p class="text-gray-400 mb-2">Geschäftsführerin & CTO</p>
                            <p class="text-gray-300 text-sm">
                                Verantwortlich für Technik und Datenschutz
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legal Information -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-white mb-8">Rechtliche Hinweise</h2>
                
                <div class="space-y-6">
                    
                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Haftungsausschluss</h3>
                        <p class="text-gray-300 leading-relaxed">
                            Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, 
                            Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen. 
                            Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten 
                            nach den allgemeinen Gesetzen verantwortlich.
                        </p>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Haftung für Links</h3>
                        <p class="text-gray-300 leading-relaxed">
                            Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen 
                            Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. 
                            Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der 
                            Seiten verantwortlich.
                        </p>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Urheberrecht</h3>
                        <p class="text-gray-300 leading-relaxed">
                            Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen 
                            dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art 
                            der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen 
                            Zustimmung des jeweiligen Autors bzw. Erstellers.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Dispute Resolution -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-white mb-8">Streitbeilegung</h2>
                <div class="bg-gray-800 rounded-xl p-6">
                    <p class="text-gray-300 leading-relaxed mb-4">
                        Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit: 
                        <a href="https://ec.europa.eu/consumers/odr/" class="text-indigo-400 hover:text-indigo-300" target="_blank">
                            https://ec.europa.eu/consumers/odr/
                        </a>
                    </p>
                    <p class="text-gray-300 leading-relaxed">
                        Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer 
                        Verbraucherschlichtungsstelle teilzunehmen.
                    </p>
                </div>
            </div>

            <!-- Technical Information -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-white mb-8">Technische Informationen</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Hosting</h3>
                        <div class="space-y-2 text-gray-300">
                            <p><strong class="text-white">Provider:</strong> Beispiel Hosting GmbH</p>
                            <p><strong class="text-white">Standort:</strong> Deutschland</p>
                            <p><strong class="text-white">Zertifizierung:</strong> ISO 27001</p>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">Sicherheit</h3>
                        <div class="space-y-2 text-gray-300">
                            <p><strong class="text-white">SSL-Zertifikat:</strong> Aktiv</p>
                            <p><strong class="text-white">Verschlüsselung:</strong> TLS 1.3</p>
                            <p><strong class="text-white">DSGVO-konform:</strong> Ja</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Contact CTA -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8 text-center">
                <h3 class="text-2xl font-bold mb-4 text-white">Rechtliche Fragen?</h3>
                <p class="text-indigo-100 mb-6">
                    Bei rechtlichen Fragen oder Anliegen kontaktiere uns gerne direkt.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="mailto:legal@bookshare.com" class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Rechtliche Anfrage
                    </a>
                    <a href="{{ route('contact') }}" class="px-6 py-3 bg-indigo-800 text-white rounded-lg font-semibold hover:bg-indigo-900 transition-colors">
                        Allgemeiner Kontakt
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html> 