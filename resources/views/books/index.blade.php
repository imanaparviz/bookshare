<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meine Bücher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header with Add Button -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium">Ihre Buchsammlung</h3>
                        <a href="{{ route('books.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Neues Buch hinzufügen
                        </a>
                    </div>
                </div>
            </div>

            <!-- Books Grid -->
            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($books as $book)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <!-- Book Cover -->
                                @if($book->image_path)
                                    <img src="{{ asset('storage/' . $book->image_path) }}" 
                                         alt="Cover von {{ $book->title }}" 
                                         class="w-full h-48 object-cover rounded mb-4">
                                @else
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 rounded mb-4 flex items-center justify-center">
                                        <span class="text-gray-500 dark:text-gray-400">Kein Cover</span>
                                    </div>
                                @endif

                                <!-- Book Info -->
                                <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $book->title }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    von {{ $book->author }}
                                </p>
                                
                                <!-- Status Badge -->
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                    @if($book->status === 'verfügbar') bg-green-100 text-green-800
                                    @elseif($book->status === 'ausgeliehen') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($book->status) }}
                                </span>

                                <!-- Action Buttons -->
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('books.show', $book) }}" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-2 px-3 rounded">
                                        Details
                                    </a>
                                    <a href="{{ route('books.edit', $book) }}" 
                                       class="bg-gray-500 hover:bg-gray-700 text-white text-xs font-bold py-2 px-3 rounded">
                                        Bearbeiten
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            Keine Bücher vorhanden
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Sie haben noch keine Bücher zu Ihrer Sammlung hinzugefügt.
                        </p>
                        <a href="{{ route('books.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Erstes Buch hinzufügen
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 