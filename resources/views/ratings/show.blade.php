<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bewertungen für "{{ $book->title }}"
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Book Info Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center space-x-6">
                        <!-- Book Cover -->
                        <div class="flex-shrink-0">
                            @if($book->image_path)
                                @if(str_starts_with($book->image_path, 'images/'))
                                    <img src="{{ asset($book->image_path) }}" 
                                         alt="Cover von {{ $book->title }}" 
                                         class="w-24 h-32 object-cover rounded-lg shadow-lg">
                                @else
                                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                                         alt="Cover von {{ $book->title }}" 
                                         class="w-24 h-32 object-cover rounded-lg shadow-lg">
                                @endif
                            @else
                                <div class="w-24 h-32 bg-gray-200 dark:bg-gray-600 rounded-lg shadow-lg flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">Kein Cover</span>
                                </div>
                            @endif
                        </div>

                        <!-- Book Details -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                {{ $book->title }}
                            </h1>
                            <p class="text-lg text-gray-600 dark:text-gray-400 mb-4">
                                von {{ $book->author }}
                            </p>

                            <!-- Rating Summary -->
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center space-x-3">
                                    <span class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $ratingStats['average'] ?: '0.0' }}
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
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('books.show', $book) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                ← Zurück zum Buch
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution -->
            @if($ratingStats['total'] > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Bewertungsverteilung</h3>
                        <div class="space-y-2">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $ratingStats['distribution'][$i] ?? 0;
                                    $percentage = $ratingStats['total'] > 0 ? ($count / $ratingStats['total']) * 100 : 0;
                                @endphp
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm w-8">{{ $i }}★</span>
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                        <div class="bg-yellow-400 h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 w-12">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            <!-- All Ratings -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">
                        Alle Bewertungen ({{ $ratings->total() }})
                    </h3>

                    @if($ratings->count() > 0)
                        <div class="space-y-6">
                            @foreach($ratings as $rating)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <!-- User Info -->
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                                                                 @if($rating->user->avatar && !$rating->is_anonymous)
                                                     <img src="{{ asset('storage/' . $rating->user->avatar) }}" 
                                                          alt="{{ $rating->is_anonymous ? 'Anonym' : $rating->user->name }}" 
                                                          class="h-10 w-10 rounded-full">
                                                 @else
                                                     <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                         <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                                             {{ $rating->is_anonymous ? '?' : strtoupper(substr($rating->user->name, 0, 1)) }}
                                                         </span>
                                                     </div>
                                                 @endif
                                            </div>
                                            <div>
                                                                                                 <div class="flex items-center space-x-2 mb-1">
                                                     <span class="font-medium text-gray-900 dark:text-gray-100">
                                                         @if($rating->is_anonymous)
                                                             Anonym
                                                         @else
                                                             {{ $rating->user->name }}
                                                         @endif
                                                     </span>
                                                     @if($rating->is_anonymous)
                                                         <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded-full">
                                                             Anonym
                                                         </span>
                                                     @endif
                                                 </div>
                                                <div class="flex items-center space-x-2">
                                                    <x-star-rating 
                                                        :rating="$rating->rating" 
                                                        size="sm" 
                                                        readonly="true" />
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $rating->created_at->format('d.m.Y H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rating Score -->
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                                {{ $rating->rating }}/5
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Review Text -->
                                    @if($rating->review)
                                        <div class="mt-4">
                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                                "{{ $rating->review }}"
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-4">
                                            <p class="text-gray-500 dark:text-gray-500 italic text-sm">
                                                Keine schriftliche Bewertung hinterlassen.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($ratings->hasPages())
                            <div class="mt-8">
                                {{ $ratings->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 dark:text-gray-500 text-6xl mb-4">📚</div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                Noch keine Bewertungen
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Seien Sie der Erste, der dieses Buch bewertet!
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('books.show', $book) }}" 
                                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                                    Jetzt bewerten
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 