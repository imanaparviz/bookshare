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

                                <!-- DEBUG INFO (temporarily visible) -->
                                <div class="mb-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded text-sm">
                                    <strong>Debug Info:</strong><br>
                                    Book Owner ID: {{ $book->owner_id }}<br>
                                    Current User ID: {{ auth()->id() ?? 'Not logged in' }}<br>
                                    Book Status: {{ $book->status }}<br>
                                    Is Owner: {{ $book->owner_id === auth()->id() ? 'Yes' : 'No' }}<br>
                                    Status is 'verfügbar': {{ $book->status === 'verfügbar' ? 'Yes' : 'No' }}
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