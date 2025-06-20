<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rating Debug - BookShare</title>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Rating System Debug</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Test Book -->
        @php
            $testBook = \App\Models\Book::first();
        @endphp

        @if($testBook)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Test Buch: {{ $testBook->title }}</h2>
                
                <!-- Debug Form -->
                <form method="POST" action="{{ route('ratings.store', $testBook) }}" 
                      x-data="{ 
                          rating: 0, 
                          isAnonymous: false,
                          review: '' 
                      }"
                      class="space-y-4">
                    @csrf
                    
                    <!-- Rating Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bewertung auswählen:
                        </label>
                        <div class="flex space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        @click="rating = {{ $i }}"
                                        :class="rating >= {{ $i }} ? 'text-yellow-500' : 'text-gray-300'"
                                        class="text-2xl hover:text-yellow-400">
                                    ★
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                        <p class="text-sm text-gray-600 mt-1">Aktuelle Bewertung: <span x-text="rating"></span>/5</p>
                    </div>

                    <!-- Review Text -->
                    <div>
                        <label for="review" class="block text-sm font-medium text-gray-700 mb-2">
                            Rezension (optional):
                        </label>
                        <textarea name="review" 
                                  id="review" 
                                  rows="3" 
                                  x-model="review"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Ihre Meinung zu diesem Buch..."></textarea>
                        <p class="text-sm text-gray-600 mt-1">Zeichen: <span x-text="review.length"></span></p>
                    </div>

                    <!-- Anonymous Checkbox -->
                    <div class="space-y-2">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       name="is_anonymous" 
                                       id="is_anonymous" 
                                       value="1"
                                       x-model="isAnonymous"
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_anonymous" class="font-medium text-gray-700">
                                    Bewertung anonym veröffentlichen
                                </label>
                                <p class="text-gray-500">
                                    Ihr Name wird nicht angezeigt, wenn diese Option aktiviert ist.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Debug Info -->
                        <div class="bg-gray-50 p-3 rounded text-sm">
                            <strong>Debug Info:</strong><br>
                            Anonym-Status: <span x-text="isAnonymous ? 'JA' : 'NEIN'"></span><br>
                            Checkbox-Wert: <span x-text="isAnonymous"></span><br>
                            Form wird senden: is_anonymous = <span x-text="isAnonymous ? '1' : '0'"></span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex space-x-4">
                        <button type="submit" 
                                :disabled="rating === 0"
                                :class="rating === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                                class="px-4 py-2 text-white font-medium rounded-md transition-colors">
                            Bewertung abgeben
                        </button>
                        
                        <button type="button" 
                                @click="rating = 0; isAnonymous = false; review = ''"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-md transition-colors">
                            Zurücksetzen
                        </button>
                    </div>

                    <!-- Form Data Preview -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">Form Data Preview:</h3>
                        <div class="text-sm text-blue-700 space-y-1">
                            <div>rating: <span x-text="rating"></span></div>
                            <div>review: <span x-text="review || '(leer)'"></span></div>
                            <div>is_anonymous: <span x-text="isAnonymous ? '1' : '0'"></span></div>
                            <div>book_id: {{ $testBook->id }}</div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Existing Ratings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Vorhandene Bewertungen für dieses Buch:</h3>
                
                @php
                    $ratings = $testBook->ratings()->with('user')->latest()->get();
                @endphp

                @if($ratings->count() > 0)
                    <div class="space-y-4">
                        @foreach($ratings as $rating)
                            <div class="border border-gray-200 rounded p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium">
                                                @if($rating->is_anonymous)
                                                    <span class="text-gray-500">Anonym</span>
                                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">ANONYM</span>
                                                @else
                                                    {{ $rating->user->name }}
                                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">ÖFFENTLICH</span>
                                                @endif
                                            </span>
                                            <span class="text-yellow-500">
                                                @for($i = 1; $i <= 5; $i++)
                                                    {{ $i <= $rating->rating ? '★' : '☆' }}
                                                @endfor
                                            </span>
                                            <span class="text-sm text-gray-500">{{ $rating->rating }}/5</span>
                                        </div>
                                        @if($rating->review)
                                            <p class="text-gray-700 mt-2">{{ $rating->review }}</p>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $rating->created_at->format('d.m.Y H:i') }}
                                    </div>
                                </div>
                                
                                <!-- Debug Info for Rating -->
                                <div class="mt-3 bg-gray-50 p-2 rounded text-xs">
                                    <strong>Debug:</strong> 
                                    is_anonymous = {{ $rating->is_anonymous ? 'true' : 'false' }} ({{ $rating->is_anonymous ? '1' : '0' }})
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Noch keine Bewertungen vorhanden.</p>
                @endif
            </div>
        @else
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                Kein Testbuch gefunden. Bitte fügen Sie zuerst ein Buch hinzu.
            </div>
        @endif

        <!-- Navigation -->
        <div class="mt-8">
            <a href="{{ route('welcome') }}" class="text-blue-600 hover:text-blue-800">
                ← Zurück zur Startseite
            </a>
        </div>
    </div>
</body>
</html> 