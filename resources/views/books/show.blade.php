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
                                                                             alt="{{ $rating->reviewer_name }}" 
                                                                             class="h-8 w-8 rounded-full">
                                                                    @else
                                                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                                            <span class="text-xs font-medium text-gray-600">
                                                                                {{ $rating->is_anonymous ? '?' : strtoupper(substr($rating->user->name, 0, 1)) }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                                            {{ $rating->reviewer_name }}
                                                                        </span>
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
                                            <form method="POST" action="{{ route('loans.store') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                    Ausleihen anfragen
                                                </button>
                                            </form>
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
</x-app-layout> 