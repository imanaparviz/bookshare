<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🤖 AI-Empfehlungen für Sie
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Preferences Analysis -->
            @if(isset($userPreferences) && $userPreferences)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">📊 Ihre Lesevorlieben</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if(isset($userPreferences['favorite_genres']))
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">Lieblings-Genres</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($userPreferences['favorite_genres'] as $genre)
                                <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">{{ $genre }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if(isset($userPreferences['rating_tendency']))
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-green-900 mb-2">Bewertungsverhalten</h4>
                            <p class="text-sm text-green-700">
                                Durchschnittliche Bewertung: {{ number_format($userPreferences['rating_tendency'], 1) }} ⭐
                            </p>
                        </div>
                        @endif
                        
                        @if(isset($userPreferences['total_ratings']))
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-medium text-purple-900 mb-2">Aktivität</h4>
                            <p class="text-sm text-purple-700">
                                {{ $userPreferences['total_ratings'] }} Bücher bewertet
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- AI Recommendations -->
            @if(isset($recommendations) && $recommendations->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">🎯 Personalisierte Empfehlungen</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recommendations as $recommendation)
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-4">
                                <!-- Book Cover -->
                                <div class="flex-shrink-0">
                                    @if($recommendation['book']->image_path)
                                        @if(str_starts_with($recommendation['book']->image_path, 'images/'))
                                            <img src="{{ asset($recommendation['book']->image_path) }}" 
                                                 alt="{{ $recommendation['book']->title }}" 
                                                 class="w-16 h-20 object-cover rounded">
                                        @else
                                            <img src="{{ asset('storage/' . $recommendation['book']->image_path) }}" 
                                                 alt="{{ $recommendation['book']->title }}" 
                                                 class="w-16 h-20 object-cover rounded">
                                        @endif
                                    @else
                                        <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Book Info -->
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 mb-1">{{ $recommendation['book']->title }}</h4>
                                    <p class="text-sm text-gray-600 mb-2">{{ $recommendation['book']->author }}</p>
                                    
                                    <!-- Rating & Genre -->
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs">
                                            {{ $recommendation['book']->genre }}
                                        </span>
                                        @if($recommendation['book']->ratings_count > 0)
                                        <div class="flex items-center">
                                            <span class="text-yellow-500 mr-1">⭐</span>
                                            <span class="text-sm text-gray-600">
                                                {{ number_format($recommendation['book']->ratings_avg_rating, 1) }}
                                                ({{ $recommendation['book']->ratings_count }})
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Recommendation Score -->
                                    <div class="mb-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Empfehlungsscore</span>
                                            <span class="text-xs font-medium text-blue-600">
                                                {{ number_format($recommendation['final_score'], 1) }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: {{ number_format($recommendation['final_score'], 0) }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Reason -->
                                    <p class="text-xs text-gray-500 mb-3">{{ $recommendation['reason'] }}</p>
                                    
                                    <!-- Actions -->
                                    <div class="flex space-x-2">
                                        <a href="{{ route('books.show', $recommendation['book']) }}" 
                                           class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-700 transition-colors">
                                            Details
                                        </a>
                                        @if($recommendation['book']->status === 'verfügbar' && $recommendation['book']->owner_id !== auth()->id())
                                        <form method="POST" action="{{ route('loans.store') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $recommendation['book']->id }}">
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-green-600 text-white rounded-md text-xs hover:bg-green-700 transition-colors">
                                                Ausleihen
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Trending Books -->
            @if(isset($trendingBooks) && $trendingBooks->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">📈 Trending Bücher</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($trendingBooks as $book)
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <!-- Book Cover -->
                            <div class="mb-3">
                                @if($book->image_path)
                                    @if(str_starts_with($book->image_path, 'images/'))
                                        <img src="{{ asset($book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full h-32 object-cover rounded">
                                    @else
                                        <img src="{{ asset('storage/' . $book->image_path) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full h-32 object-cover rounded">
                                    @endif
                                @else
                                    <div class="w-full h-32 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Book Info -->
                            <h4 class="font-medium text-gray-900 mb-1 text-sm">{{ Str::limit($book->title, 30) }}</h4>
                            <p class="text-xs text-gray-600 mb-2">{{ $book->author }}</p>
                            
                            @if($book->ratings_count > 0)
                            <div class="flex items-center mb-2">
                                <span class="text-yellow-500 mr-1">⭐</span>
                                <span class="text-xs text-gray-600">
                                    {{ number_format($book->ratings_avg_rating, 1) }}
                                    ({{ $book->ratings_count }})
                                </span>
                            </div>
                            @endif
                            
                            <a href="{{ route('books.show', $book) }}" 
                               class="block w-full text-center px-3 py-1 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-700 transition-colors">
                                Details
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- No Recommendations -->
            @if((!isset($recommendations) || $recommendations->count() === 0) && (!isset($trendingBooks) || $trendingBooks->count() === 0))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <div class="mb-4">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Keine Empfehlungen verfügbar</h3>
                        <p class="text-gray-600 mb-4">
                            Bewerten Sie einige Bücher, um personalisierte Empfehlungen zu erhalten!
                        </p>
                        <a href="{{ route('books.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Bücher durchsuchen
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout> 