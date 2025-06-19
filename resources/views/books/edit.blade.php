<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buch bearbeiten: ') }} {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Current Cover Preview -->
                        @if($book->image_path)
                            <div class="text-center">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Aktuelles Cover
                                </label>
                                <img src="{{ asset('storage/' . $book->image_path) }}" 
                                     alt="Aktuelles Cover" 
                                     class="mx-auto w-32 h-48 object-cover rounded shadow">
                            </div>
                        @endif

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Titel *')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" 
                                         :value="old('title', $book->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Author -->
                        <div>
                            <x-input-label for="author" :value="__('Autor *')" />
                            <x-text-input id="author" name="author" type="text" class="mt-1 block w-full" 
                                         :value="old('author', $book->author)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('author')" />
                        </div>

                        <!-- ISBN -->
                        <div>
                            <x-input-label for="isbn" :value="__('ISBN')" />
                            <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full" 
                                         :value="old('isbn', $book->isbn)" placeholder="978-3-16-148410-0" />
                            <x-input-error class="mt-2" :messages="$errors->get('isbn')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Beschreibung')" />
                            <textarea id="description" name="description" rows="4" 
                                     class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                     placeholder="Eine kurze Beschreibung des Buchinhalts...">{{ old('description', $book->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Genre -->
                        <div>
                            <x-input-label for="genre" :value="__('Genre')" />
                            <x-text-input id="genre" name="genre" type="text" class="mt-1 block w-full" 
                                         :value="old('genre', $book->genre)" placeholder="z.B. Roman, Krimi, Sachbuch..." />
                            <x-input-error class="mt-2" :messages="$errors->get('genre')" />
                        </div>

                        <!-- Publication Year -->
                        <div>
                            <x-input-label for="publication_year" :value="__('Erscheinungsjahr')" />
                            <x-text-input id="publication_year" name="publication_year" type="number" 
                                         class="mt-1 block w-full" :value="old('publication_year', $book->publication_year)" 
                                         min="1000" max="{{ date('Y') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('publication_year')" />
                        </div>

                        <!-- Language -->
                        <div>
                            <x-input-label for="language" :value="__('Sprache')" />
                            <select id="language" name="language" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="deutsch" {{ (old('language', $book->language) == 'deutsch') ? 'selected' : '' }}>Deutsch</option>
                                <option value="englisch" {{ (old('language', $book->language) == 'englisch') ? 'selected' : '' }}>Englisch</option>
                                <option value="französisch" {{ (old('language', $book->language) == 'französisch') ? 'selected' : '' }}>Französisch</option>
                                <option value="spanisch" {{ (old('language', $book->language) == 'spanisch') ? 'selected' : '' }}>Spanisch</option>
                                <option value="andere" {{ (old('language', $book->language) == 'andere') ? 'selected' : '' }}>Andere</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('language')" />
                        </div>

                        <!-- Condition -->
                        <div>
                            <x-input-label for="condition" :value="__('Zustand *')" />
                            <select id="condition" name="condition" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="sehr gut" {{ (old('condition', $book->condition) == 'sehr gut') ? 'selected' : '' }}>Sehr gut</option>
                                <option value="gut" {{ (old('condition', $book->condition) == 'gut') ? 'selected' : '' }}>Gut</option>
                                <option value="befriedigend" {{ (old('condition', $book->condition) == 'befriedigend') ? 'selected' : '' }}>Befriedigend</option>
                                <option value="akzeptabel" {{ (old('condition', $book->condition) == 'akzeptabel') ? 'selected' : '' }}>Akzeptabel</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('condition')" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status *')" />
                            <select id="status" name="status" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="verfügbar" {{ (old('status', $book->status) == 'verfügbar') ? 'selected' : '' }}>Verfügbar</option>
                                <option value="ausgeliehen" {{ (old('status', $book->status) == 'ausgeliehen') ? 'selected' : '' }}>Ausgeliehen</option>
                                <option value="reserviert" {{ (old('status', $book->status) == 'reserviert') ? 'selected' : '' }}>Reserviert</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <!-- Cover Image -->
                        <div>
                            <x-input-label for="cover" :value="__('Neues Buchcover (optional)')" />
                            <input id="cover" name="cover" type="file" accept="image/*" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">PNG, JPG oder JPEG. Max. 2MB. Lassen Sie das Feld leer, um das aktuelle Cover zu behalten.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('cover')" />
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('books.show', $book) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Abbrechen
                            </a>
                            <x-primary-button>
                                {{ __('Änderungen speichern') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 