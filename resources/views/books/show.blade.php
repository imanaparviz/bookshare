<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Book Cover -->
                        <div class="md:col-span-1">
                            @if($book->image_path)
                                @if(str_starts_with($book->image_path, 'images/'))
                                    {{-- Static seeded images --}}
                                    <img src="{{ asset($book->image_path) }}" 
                                         alt="Cover von {{ $book->title }}" 
                                         class="w-full max-w-sm mx-auto rounded-lg shadow-lg">
                                @else
                                    {{-- User uploaded images --}}
                                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                                         alt="Cover von {{ $book->title }}" 
                                         class="w-full max-w-sm mx-auto rounded-lg shadow-lg">
                                @endif
                            @else
                                <div class="w-full max-w-sm mx-auto h-96 bg-gray-200 dark:bg-gray-600 rounded-lg shadow-lg flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400 text-lg">Kein Cover verfügbar</span>
                                </div>
                            @endif
                        </div>

                        <!-- Book Details -->
                        <div class="md:col-span-2">
                            <div class="space-y-6">
                                <!-- Title and Author -->
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                        {{ $book->title }}
                                    </h1>
                                    <p class="text-xl text-gray-600 dark:text-gray-400">
                                        von {{ $book->author }}
                                    </p>
                                </div>

                                <!-- Status and Condition -->
                                <div class="flex space-x-4">
                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                        @if($book->status === 'verfügbar') bg-green-100 text-green-800
                                        @elseif($book->status === 'ausgeliehen') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($book->status) }}
                                    </span>
                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Zustand: {{ ucfirst($book->condition) }}
                                    </span>
                                </div>

                                <!-- Book Information -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    @if($book->isbn)
                                        <div>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300">ISBN:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ $book->isbn }}</span>
                                        </div>
                                    @endif

                                    @if($book->genre)
                                        <div>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300">Genre:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ $book->genre }}</span>
                                        </div>
                                    @endif

                                    @if($book->publication_year)
                                        <div>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300">Erscheinungsjahr:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ $book->publication_year }}</span>
                                        </div>
                                    @endif

                                    @if($book->language)
                                        <div>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300">Sprache:</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ ucfirst($book->language) }}</span>
                                        </div>
                                    @endif

                                    <div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-300">Besitzer:</span>
                                        <span class="text-gray-600 dark:text-gray-400">{{ $book->owner->name }}</span>
                                    </div>

                                    <div>
                                        <span class="font-semibold text-gray-700 dark:text-gray-300">Hinzugefügt:</span>
                                        <span class="text-gray-600 dark:text-gray-400">{{ $book->created_at->format('d.m.Y') }}</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($book->description)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            Beschreibung
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                            {{ $book->description }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Rating Section -->
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                        Bewertungen
                                    </h3>
                                    
                                    <!-- Rating Stats -->
                                                                            <div class="flex items-center space-x-6 mb-6">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                                {{ number_format($ratingStats['average'] ?: 0, 1) }}
                                            </span>
                                            <div class="flex flex-col">
                                                <x-star-rating 
                                                    :rating="round($ratingStats['average'])" 
                                                    size="lg" 
                                                    readonly="true" />
                                                <span class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    Durchschnittsbewertung
                                                </span>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            ({{ $ratingStats['total'] }} {{ $ratingStats['total'] === 1 ? 'Bewertung' : 'Bewertungen' }})
                                        </span>
                                        @if($ratingStats['total'] > 0)
                                            <a href="{{ route('ratings.show', $book) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Alle anzeigen →
                                            </a>
                                        @endif
                                    </div>

                                    <!-- User Rating Form -->
                                    @if($book->owner_id !== auth()->id())
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                                @if($ratingStats['user_rating'])
                                                    Ihre Bewertung bearbeiten
                                                @else
                                                    Dieses Buch bewerten
                                                @endif
                                            </h4>
                                            
                                            <form method="POST" action="{{ route('ratings.store', $book) }}" class="space-y-4">
                                                @csrf
                                                
                                                <!-- Star Rating -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                                        Wie hat Ihnen das Buch gefallen?
                                                    </label>
                                                    <x-star-rating 
                                                        :rating="$ratingStats['user_rating']->rating ?? 0" 
                                                        size="lg" 
                                                        name="rating" />
                                                </div>

                                                <!-- Review Text -->
                                                <div>
                                                    <label for="review" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                        Rezension (optional)
                                                    </label>
                                                    <textarea name="review" id="review" rows="3" 
                                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100"
                                                              placeholder="Teilen Sie Ihre Gedanken zu diesem Buch mit...">{{ $ratingStats['user_rating']->review ?? '' }}</textarea>
                                                </div>

                                                <!-- Anonymous Option -->
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="is_anonymous" id="is_anonymous" 
                                                           value="1" {{ ($ratingStats['user_rating']->is_anonymous ?? false) ? 'checked' : '' }}
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <label for="is_anonymous" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                        Bewertung anonym veröffentlichen
                                                    </label>
                                                </div>

                                                <!-- Submit Buttons -->
                                                <div class="flex items-center space-x-3">
                                                    <button type="submit" 
                                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                                        @if($ratingStats['user_rating'])
                                                            Bewertung aktualisieren
                                                        @else
                                                            Bewertung abgeben
                                                        @endif
                                                    </button>
                                                    
                                                    @if($ratingStats['user_rating'])
                                                        <form method="POST" action="{{ route('ratings.destroy', $book) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    onclick="return confirm('Möchten Sie Ihre Bewertung wirklich löschen?')"
                                                                    class="text-red-600 hover:text-red-800 font-medium py-2 px-4 rounded-md transition-colors">
                                                                Bewertung löschen
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- Recent Reviews -->
                                    @if($recentRatings->count() > 0)
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                                Neueste Bewertungen
                                            </h4>
                                            <div class="space-y-4">
                                                @foreach($recentRatings as $rating)
                                                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex items-center space-x-3">
                                                                <div class="flex-shrink-0">
                                                                    @if($rating->user->avatar && !$rating->is_anonymous)
                                                                        <img src="{{ asset('storage/' . $rating->user->avatar) }}" 
                                                                             alt="{{ $rating->is_anonymous ? 'Anonym' : $rating->user->name }}" 
                                                                             class="h-8 w-8 rounded-full">
                                                                    @else
                                                                        <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                                {{ $rating->is_anonymous ? '?' : strtoupper(substr($rating->user->name, 0, 1)) }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                                            @if($rating->is_anonymous)
                                                                                Anonym
                                                                            @else
                                                                                {{ $rating->user->name }}
                                                                            @endif
                                                                        </span>
                                                                        @if($rating->is_anonymous)
                                                                            <span class="inline-block px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded-full">
                                                                                Anonym
                                                                            </span>
                                                                        @endif
                                                                        <x-star-rating 
                                                                            :rating="$rating->rating" 
                                                                            size="sm" 
                                                                            readonly="true" />
                                                                    </div>
                                                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                                                        {{ $rating->created_at->format('d.m.Y') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($rating->review)
                                                            <p class="mt-3 text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                                                                {{ $rating->review }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    @if($book->owner_id === auth()->id())
                                        <!-- Owner buttons -->
                                        <a href="{{ route('books.edit', $book) }}" 
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Bearbeiten
                                        </a>
                                        
                                        <form method="POST" action="{{ route('books.destroy', $book) }}" 
                                              onsubmit="return confirm('Sind Sie sicher, dass Sie dieses Buch löschen möchten?')" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Löschen
                                            </button>
                                        </form>
                                    @else
                                        <!-- Non-owner buttons -->
                                        @if($book->status === 'verfügbar')
                                            <!-- Enhanced Loan Request Button -->
                                            <button type="button" 
                                                    onclick="openLoanRequestModal()"
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                                📚 Ausleihen anfragen
                                            </button>
                                        @else
                                            <span class="inline-block px-4 py-2 bg-gray-200 text-gray-600 rounded">
                                                Nicht verfügbar zum Ausleihen (Status: {{ $book->status }})
                                            </span>
                                        @endif
                                    @endif

                                    <a href="{{ route('books.index') }}" 
                                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                        Zurück zur Übersicht
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations Section -->
            @if(!empty($recommendations) && count($recommendations) > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Ähnliche Bücher, die Ihnen gefallen könnten</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($recommendations as $recommendation)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    @if($recommendation['image_path'])
                                        @if(str_starts_with($recommendation['image_path'], 'images/'))
                                            {{-- Static seeded images --}}
                                            <img src="{{ asset($recommendation['image_path']) }}" 
                                                 alt="Cover von {{ $recommendation['title'] }}" 
                                                 class="w-full h-32 object-cover rounded mb-3">
                                        @else
                                            {{-- User uploaded images --}}
                                            <img src="{{ asset('storage/' . $recommendation['image_path']) }}" 
                                                 alt="Cover von {{ $recommendation['title'] }}" 
                                                 class="w-full h-32 object-cover rounded mb-3">
                                        @endif
                                    @else
                                        <div class="w-full h-32 bg-gray-200 dark:bg-gray-600 rounded mb-3 flex items-center justify-center">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">Kein Cover</span>
                                        </div>
                                    @endif
                                    
                                    <h4 class="font-semibold text-sm mb-1">{{ $recommendation['title'] }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs mb-2">{{ $recommendation['author'] }}</p>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                            {{ ucfirst($recommendation['genre'] ?? 'Allgemein') }}
                                        </span>
                                        <a href="{{ route('books.show', $recommendation['id']) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Details →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Loan Request Modal -->
    @if($book->status === 'verfügbar' && $book->owner_id !== auth()->id())
    <style>
        @media (max-width: 640px) {
            .modal-content {
                max-height: calc(100vh - 2rem);
                overflow-y: auto;
            }
            
            /* Better touch targets for mobile */
            .modal-content button,
            .modal-content select,
            .modal-content input,
            .modal-content textarea {
                min-height: 44px;
            }
            
            /* Prevent zoom on input focus on iOS */
            .modal-content input,
            .modal-content select,
            .modal-content textarea {
                font-size: 16px;
            }
        }
    </style>
    <div id="loanRequestModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4">
        <div class="modal-content relative top-4 mx-auto p-4 sm:p-6 border w-full max-w-lg sm:max-w-xl lg:max-w-2xl shadow-lg rounded-md bg-white dark:bg-gray-800 my-8">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-gray-100 pr-4 leading-tight">
                        📚 Ausleihantrag für<br class="sm:hidden"> 
                        <span class="block sm:inline">"{{ Str::limit($book->title, 30) }}"</span>
                    </h3>
                    <button onclick="closeLoanRequestModal()" class="text-gray-400 hover:text-gray-600 flex-shrink-0 p-1">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Loan Request Form -->
                <form method="POST" action="{{ route('loans.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    
                    <!-- Owner Info -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-center space-x-3">
                            @if($book->owner->avatar)
                                <img src="{{ asset('storage/' . $book->owner->avatar) }}" 
                                     alt="{{ $book->owner->name }}" 
                                     class="h-8 w-8 sm:h-10 sm:w-10 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-300">
                                        {{ strtoupper(substr($book->owner->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                    Anfrage an: {{ $book->owner->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Buchbesitzer
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Duration and Pickup Method - Responsive Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Desired Duration -->
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                ⏰ Ausleihdauer
                            </label>
                            <select name="duration" id="duration" 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                <option value="1">1 Woche</option>
                                <option value="2" selected>2 Wochen (Standard)</option>
                                <option value="3">3 Wochen</option>
                                <option value="4">1 Monat</option>
                                <option value="custom">Andere</option>
                            </select>
                        </div>

                        <!-- Pickup Method -->
                        <div>
                            <label for="pickup_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                📍 Übergabe
                            </label>
                            <select name="pickup_method" id="pickup_method" 
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                <option value="pickup">Ich hole ab</option>
                                <option value="meet">Treffen vereinbaren</option>
                                <option value="delivery">Lieferung möglich?</option>
                                <option value="discuss">In Nachricht besprechen</option>
                            </select>
                        </div>
                    </div>

                    <!-- Personal Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            💬 Persönliche Nachricht
                        </label>
                        <textarea name="message" id="message" rows="3" class="sm:rows-4"
                                  placeholder="Hallo! Ich würde gerne Ihr Buch ausleihen..."
                                  class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100 resize-none"></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            💡 Eine persönliche Nachricht erhöht die Erfolgschance!
                        </p>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <label for="contact_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            📞 Kontakt (optional)
                        </label>
                        <input type="text" name="contact_info" id="contact_info" 
                               placeholder="Tel., WhatsApp, etc."
                               class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 pt-4">
                        <button type="submit" 
                                class="w-full sm:flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors text-sm sm:text-base">
                            📤 Anfrage senden
                        </button>
                        <button type="button" 
                                onclick="closeLoanRequestModal()"
                                class="w-full sm:flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors text-sm sm:text-base">
                            Abbrechen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script>
        function openLoanRequestModal() {
            document.getElementById('loanRequestModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Focus trap for accessibility
            const modal = document.getElementById('loanRequestModal');
            const firstInput = modal.querySelector('input, select, textarea');
            if (firstInput) firstInput.focus();
        }

        function closeLoanRequestModal() {
            document.getElementById('loanRequestModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('loanRequestModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoanRequestModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('loanRequestModal').classList.contains('hidden')) {
                closeLoanRequestModal();
            }
        });

        // Auto-resize textarea on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('message');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            }
        });
    </script>
</x-app-layout> 