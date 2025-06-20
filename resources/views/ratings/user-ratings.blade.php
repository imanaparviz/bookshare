<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meine Bewertungen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($ratings->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($ratings as $rating)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-lg transition-shadow">
                                    <!-- Book Cover -->
                                    <div class="mb-4">
                                        @if($rating->book->image_path)
                                            @if(str_starts_with($rating->book->image_path, 'images/'))
                                                <img src="{{ asset($rating->book->image_path) }}" 
                                                     alt="Cover von {{ $rating->book->title }}" 
                                                     class="w-full h-48 object-cover rounded-lg">
                                            @else
                                                <img src="{{ asset('storage/' . $rating->book->image_path) }}" 
                                                     alt="Cover von {{ $rating->book->title }}" 
                                                     class="w-full h-48 object-cover rounded-lg">
                                            @endif
                                        @else
                                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-500 dark:text-gray-400">Kein Cover</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Book Info -->
                                    <div class="mb-4">
                                        <h3 class="font-semibold text-lg mb-1">
                                            <a href="{{ route('books.show', $rating->book) }}" 
                                               class="text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ $rating->book->title }}
                                            </a>
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                                            von {{ $rating->book->author }}
                                        </p>
                                    </div>

                                    <!-- Rating -->
                                    <div class="mb-4">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <x-star-rating 
                                                :rating="$rating->rating" 
                                                size="md" 
                                                readonly="true" />
                                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                                {{ $rating->rating }}/5
                                            </span>
                                            @if($rating->is_anonymous)
                                                <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">
                                                    Anonym
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Bewertet am {{ $rating->created_at->format('d.m.Y') }}
                                        </p>
                                    </div>

                                    <!-- Review Text -->
                                    @if($rating->review)
                                        <div class="mb-4">
                                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                                "{{ $rating->review }}"
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <a href="{{ route('books.show', $rating->book) }}" 
                                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Buch ansehen →
                                        </a>
                                        
                                        <form method="POST" action="{{ route('ratings.destroy', $rating->book) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Möchten Sie diese Bewertung wirklich löschen?')"
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Löschen
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $ratings->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                Keine Bewertungen vorhanden
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Sie haben noch keine Bücher bewertet.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('books.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Bücher entdecken
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 