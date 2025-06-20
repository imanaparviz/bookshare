<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ausleihe Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Book Cover -->
                        <div class="md:col-span-1">
                            @if($loan->book->image_path)
                                <img src="{{ asset('storage/' . $loan->book->image_path) }}" 
                                     alt="Cover von {{ $loan->book->title }}" 
                                     class="w-full max-w-sm mx-auto rounded-lg shadow-lg">
                            @else
                                <div class="w-full max-w-sm mx-auto h-96 bg-gray-200 dark:bg-gray-600 rounded-lg shadow-lg flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400 text-lg">Kein Cover verfügbar</span>
                                </div>
                            @endif
                        </div>

                        <!-- Loan Details -->
                        <div class="md:col-span-2">
                            <div class="space-y-6">
                                <!-- Book Information -->
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                        {{ $loan->book->title }}
                                    </h1>
                                    <p class="text-xl text-gray-600 dark:text-gray-400">
                                        von {{ $loan->book->author }}
                                    </p>
                                </div>

                                <!-- Status -->
                                <div>
                                    <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full
                                        @if($loan->status === 'aktiv') bg-green-100 text-green-800
                                        @elseif($loan->status === 'angefragt') bg-yellow-100 text-yellow-800
                                        @elseif($loan->status === 'abgelehnt') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        Status: {{ ucfirst($loan->status) }}
                                    </span>
                                </div>

                                <!-- Participants -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            Verleiher
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $loan->lender->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-500">
                                            {{ $loan->lender->email }}
                                        </p>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            Ausleiher
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $loan->borrower->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-500">
                                            {{ $loan->borrower->email }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Dates -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            Ausleihtermin
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $loan->loan_date ? $loan->loan_date->format('d.m.Y') : 'Noch nicht festgelegt' }}
                                        </p>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            Fälligkeitsdatum
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 
                                            {{ $loan->is_overdue ? 'text-red-600 font-bold' : '' }}">
                                            {{ $loan->due_date ? $loan->due_date->format('d.m.Y') : 'Noch nicht festgelegt' }}
                                            @if($loan->is_overdue)
                                                <span class="text-red-500 font-bold">(Überfällig)</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($loan->return_date)
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                Rückgabedatum
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-400">
                                                {{ $loan->return_date->format('d.m.Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Notes -->
                                @if($loan->notes)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            Notizen
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                                            {{ $loan->notes }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    @if($loan->lender_id === auth()->id() && $loan->status === 'angefragt')
                                        <!-- Lender actions for pending requests -->
                                        <form method="POST" action="{{ route('loans.update', $loan) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" 
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                Genehmigen
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('loans.update', $loan) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="deny">
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                Ablehnen
                                            </button>
                                        </form>
                                    @endif

                                    @if($loan->borrower_id === auth()->id() && $loan->status === 'aktiv')
                                        <!-- Borrower return action -->
                                        <form method="POST" action="{{ route('loans.update', $loan) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="return">
                                            <button type="submit" 
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                                    onclick="return confirm('Möchten Sie dieses Buch wirklich zurückgeben?')">
                                                Buch zurückgeben
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('loans.index') }}" 
                                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                        Zurück zu Ausleihen
                                    </a>
                                    
                                    <a href="{{ route('books.show', $loan->book) }}" 
                                       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                        Buch Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 