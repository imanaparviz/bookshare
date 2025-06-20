<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datenschutz - BookShare</title>
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
    <section class="relative py-20 bg-gradient-to-br from-slate-900 via-gray-900 to-zinc-900">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">Datenschutzerklärung</h1>
            <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                Transparenz über den Umgang mit deinen Daten bei BookShare.
            </p>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="py-16 bg-gray-900">
        <div class="max-w-4xl mx-auto px-6">
            
            <!-- Last Updated -->
            <div class="bg-gray-800 rounded-xl p-6 mb-8">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Letzte Aktualisierung</h3>
                        <p class="text-gray-400">19. Juni 2025</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="prose prose-invert max-w-none">
                
                <!-- Section 1 -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">1. Überblick</h2>
                    <div class="bg-gray-800 rounded-xl p-6">
                        <p class="text-gray-300 leading-relaxed mb-4">
                            BookShare respektiert deine Privatsphäre und ist bestrebt, deine persönlichen Daten zu schützen. 
                            Diese Datenschutzerklärung informiert dich darüber, wie wir deine Daten sammeln, verwenden und schützen, 
                            wenn du unsere Plattform nutzt.
                        </p>
                        <p class="text-gray-300 leading-relaxed">
                            Wir sammeln nur die Daten, die für den Betrieb unserer Buchsharing-Plattform notwendig sind, 
                            und behandeln alle Informationen vertraulich gemäß der Datenschutz-Grundverordnung (DSGVO).
                        </p>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">2. Welche Daten wir sammeln</h2>
                    <div class="space-y-6">
                        
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h3 class="text-xl font-semibold text-white mb-4">Registrierungsdaten</h3>
                            <ul class="list-disc list-inside text-gray-300 space-y-2">
                                <li>Name und E-Mail-Adresse</li>
                                <li>Gewähltes Passwort (verschlüsselt gespeichert)</li>
                                <li>Profilbild (optional)</li>
                                <li>Standortinformationen (Stadt/Region für lokale Matches)</li>
                            </ul>
                        </div>

                        <div class="bg-gray-800 rounded-xl p-6">
                            <h3 class="text-xl font-semibold text-white mb-4">Buchbezogene Daten</h3>
                            <ul class="list-disc list-inside text-gray-300 space-y-2">
                                <li>Informationen zu deinen Büchern (Titel, Autor, ISBN, Zustand)</li>
                                <li>Buchfotos und Beschreibungen</li>
                                <li>Ausleih- und Verleihhistorie</li>
                                <li>Bewertungen und Kommentare</li>
                            </ul>
                        </div>

                        <div class="bg-gray-800 rounded-xl p-6">
                            <h3 class="text-xl font-semibold text-white mb-4">Nutzungsdaten</h3>
                            <ul class="list-disc list-inside text-gray-300 space-y-2">
                                <li>IP-Adresse und Browser-Informationen</li>
                                <li>Seitenaufrufe und Klickverhalten</li>
                                <li>Geräteinformationen (Typ, Betriebssystem)</li>
                                <li>Cookies und ähnliche Technologien</li>
                            </ul>
                        </div>

                    </div>
                </div>

                <!-- Section 3 -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">3. Wie wir deine Daten verwenden</h2>
                    <div class="bg-gray-800 rounded-xl p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-3">Plattform-Betrieb</h4>
                                <ul class="list-disc list-inside text-gray-300 space-y-1 text-sm">
                                    <li>Kontoerstellung und -verwaltung</li>
                                    <li>Bücher-Matching und Empfehlungen</li>
                                    <li>Kommunikation zwischen Nutzern</li>
                                    <li>Transaktionsabwicklung</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-3">Verbesserung</h4>
                                <ul class="list-disc list-inside text-gray-300 space-y-1 text-sm">
                                    <li>Analyse der Plattform-Nutzung</li>
                                    <li>Entwicklung neuer Features</li>
                                    <li>Personalisierung der Erfahrung</li>
                                    <li>Fehlerbehebung und Support</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4 -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">4. Datenweitergabe</h2>
                    <div class="bg-gray-800 rounded-xl p-6">
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Wir verkaufen oder vermieten deine persönlichen Daten niemals an Dritte. 
                            Eine Weitergabe erfolgt nur in folgenden Fällen:
                        </p>
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            <li><strong class="text-white">Mit deiner Einwilligung:</strong> Wenn du explizit zustimmst</li>
                            <li><strong class="text-white">Für den Service:</strong> Andere Nutzer sehen deine öffentlichen Profilinformationen</li>
                            <li><strong class="text-white">Rechtliche Anforderungen:</strong> Bei behördlichen Anordnungen</li>
                            <li><strong class="text-white">Dienstleister:</strong> Vertrauenswürdige Partner für technische Services (unter Vertraulichkeitsvereinbarungen)</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 5 -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">5. Deine Rechte</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Auskunft & Zugang</h3>
                            <p class="text-gray-300 text-sm mb-3">
                                Du hast das Recht zu erfahren, welche Daten wir über dich gespeichert haben.
                            </p>
                            <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-sm font-semibold transition-colors">
                                Datenauskunft anfordern
                            </button>
                        </div>

                        <div class="bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Berichtigung</h3>
                            <p class="text-gray-300 text-sm mb-3">
                                Unrichtige oder unvollständige Daten können jederzeit korrigiert werden.
                            </p>
                            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-semibold transition-colors">
                                Profil bearbeiten
                            </button>
                        </div>

                        <div class="bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Löschung</h3>
                            <p class="text-gray-300 text-sm mb-3">
                                Du kannst die Löschung deiner Daten verlangen, soweit keine gesetzlichen Aufbewahrungspflichten bestehen.
                            </p>
                            <button class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-semibold transition-colors">
                                Konto löschen
                            </button>
                        </div>

                        <div class="bg-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Portabilität</h3>
                            <p class="text-gray-300 text-sm mb-3">
                                Du kannst deine Daten in einem strukturierten, maschinenlesbaren Format erhalten.
                            </p>
                            <button class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-sm font-semibold transition-colors">
                                Daten exportieren
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Section 6 -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">6. Datensicherheit</h2>
                    <div class="bg-gray-800 rounded-xl p-6">
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Wir setzen technische und organisatorische Maßnahmen ein, um deine Daten vor unbefugtem Zugriff, 
                            Verlust oder Missbrauch zu schützen:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-white font-semibold mb-2">Technische Maßnahmen</h4>
                                <ul class="list-disc list-inside text-gray-300 text-sm space-y-1">
                                    <li>SSL/TLS-Verschlüsselung</li>
                                    <li>Sichere Passwort-Speicherung</li>
                                    <li>Regelmäßige Sicherheitsupdates</li>
                                    <li>Firewalls und Intrusion Detection</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold mb-2">Organisatorische Maßnahmen</h4>
                                <ul class="list-disc list-inside text-gray-300 text-sm space-y-1">
                                    <li>Mitarbeiterschulungen</li>
                                    <li>Zugriffskontrollen</li>
                                    <li>Regelmäßige Audits</li>
                                    <li>Incident Response Plan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 7 -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">7. Cookies</h2>
                    <div class="bg-gray-800 rounded-xl p-6">
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Wir verwenden Cookies, um die Funktionalität unserer Website zu verbessern und deine Nutzererfahrung zu personalisieren:
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-green-400 rounded-full mt-2"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Notwendige Cookies</h4>
                                    <p class="text-gray-300 text-sm">Für grundlegende Website-Funktionen (immer aktiv)</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Funktionale Cookies</h4>
                                    <p class="text-gray-300 text-sm">Für erweiterte Funktionen und Personalisierung</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-purple-400 rounded-full mt-2"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Analyse-Cookies</h4>
                                    <p class="text-gray-300 text-sm">Für Nutzungsanalyse und Verbesserungen (optional)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 8 -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-white mb-6">8. Kontakt</h2>
                    <div class="bg-gray-800 rounded-xl p-6">
                        <p class="text-gray-300 leading-relaxed mb-4">
                            Bei Fragen zum Datenschutz oder zur Ausübung deiner Rechte kannst du uns kontaktieren:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-white font-semibold mb-2">Datenschutzbeauftragter</h4>
                                <p class="text-gray-300 text-sm">
                                    E-Mail: privacy@bookshare.com<br>
                                    Antwort innerhalb von 72 Stunden
                                </p>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold mb-2">Allgemeine Anfragen</h4>
                                <p class="text-gray-300 text-sm">
                                    E-Mail: support@bookshare.com<br>
                                    Telefon: +49 (0) 123 456 789
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CTA -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8 text-center">
                <h3 class="text-2xl font-bold mb-4 text-white">Fragen zum Datenschutz?</h3>
                <p class="text-indigo-100 mb-6">
                    Unser Datenschutz-Team beantwortet gerne alle deine Fragen.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="mailto:privacy@bookshare.com" class="px-6 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Datenschutz kontaktieren
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