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
                                @if(str_starts_with($loan->book->image_path, 'images/'))
                                    {{-- Static seeded images --}}
                                    <img src="{{ asset($loan->book->image_path) }}" 
                                         alt="Cover von {{ $loan->book->title }}" 
                                         class="w-full max-w-sm mx-auto rounded-lg shadow-lg">
                                @else
                                    {{-- User uploaded images --}}
                                    <img src="{{ asset('storage/' . $loan->book->image_path) }}" 
                                         alt="Cover von {{ $loan->book->title }}" 
                                         class="w-full max-w-sm mx-auto rounded-lg shadow-lg">
                                @endif
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
                                    <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2 leading-tight break-words">
                                        {{ $loan->book->title }}
                                    </h1>
                                    <p class="text-base sm:text-lg md:text-xl text-gray-600 dark:text-gray-400 break-words">
                                        von {{ $loan->book->author }}
                                    </p>
                                </div>

                                <!-- Status -->
                                <div>
                                    <span class="inline-block px-3 py-1 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold rounded-full
                                        @if($loan->status === 'aktiv') bg-green-100 text-green-800
                                        @elseif($loan->status === 'angefragt') bg-yellow-100 text-yellow-800
                                        @elseif($loan->status === 'abgelehnt') bg-red-100 text-red-800
                                        @elseif($loan->status === 'storniert') bg-orange-100 text-orange-800
                                        @elseif($loan->status === 'zurückgegeben') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        Status: {{ ucfirst($loan->status) }}
                                    </span>
                                </div>

                                <!-- Participants - Responsive grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div class="bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 rounded-lg">
                                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                            Verleiher
                                        </h3>
                                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 break-words">
                                            {{ $loan->lender->name }}
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-500 break-all">
                                            {{ $loan->lender->email }}
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 rounded-lg">
                                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                            Ausleiher
                                        </h3>
                                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 break-words">
                                            {{ $loan->borrower->name }}
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-500 break-all">
                                            {{ $loan->borrower->email }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Dates - Responsive grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div class="bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 rounded-lg">
                                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                            Ausleihtermin
                                        </h3>
                                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                                            {{ $loan->loan_date ? $loan->loan_date->format('d.m.Y') : 'Noch nicht festgelegt' }}
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 rounded-lg">
                                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                            Fälligkeitsdatum
                                        </h3>
                                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 
                                            {{ $loan->is_overdue ? 'text-red-600 font-bold' : '' }}">
                                            {{ $loan->due_date ? $loan->due_date->format('d.m.Y') : 'Noch nicht festgelegt' }}
                                            @if($loan->is_overdue)
                                                <span class="block sm:inline text-red-500 font-bold text-xs sm:text-sm">(Überfällig)</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($loan->return_date)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 rounded-lg sm:col-span-2">
                                            <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                Rückgabedatum
                                            </h3>
                                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">
                                                {{ $loan->return_date->format('d.m.Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Enhanced Communication Section -->
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 sm:pt-6">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3 sm:mb-4">
                                        💬 Kommunikation
                                    </h3>
                                    
                                    <!-- Borrower's Request Details -->
                                    @if($loan->message || $loan->contact_info || $loan->pickup_method)
                                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 sm:p-4 rounded-lg mb-3 sm:mb-4">
                                            <h4 class="text-sm sm:text-base font-medium text-blue-900 dark:text-blue-100 mb-2 sm:mb-3">
                                                📝 Anfrage von {{ $loan->borrower->name }}
                                            </h4>
                                            
                                            @if($loan->message)
                                                <div class="mb-2 sm:mb-3">
                                                    <span class="text-xs sm:text-sm font-medium text-blue-800 dark:text-blue-200">Nachricht:</span>
                                                    <p class="text-xs sm:text-sm text-blue-700 dark:text-blue-300 mt-1 break-words">{{ $loan->message }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm">
                                                @if($loan->pickup_method)
                                                    <div>
                                                        <span class="font-medium text-blue-800 dark:text-blue-200">📍 Übergabemethode:</span>
                                                        <p class="text-blue-700 dark:text-blue-300 break-words">
                                                            @switch($loan->pickup_method)
                                                                @case('pickup') Ich hole das Buch ab @break
                                                                @case('meet') Persönliches Treffen vereinbaren @break
                                                                @case('delivery') Lieferung (falls möglich) @break
                                                                @case('discuss') In Nachricht besprechen @break
                                                                @default {{ $loan->pickup_method }} @break
                                                            @endswitch
                                                        </p>
                                                    </div>
                                                @endif
                                                
                                                @if($loan->requested_duration_weeks)
                                                    <div>
                                                        <span class="font-medium text-blue-800 dark:text-blue-200">⏰ Gewünschte Dauer:</span>
                                                        <p class="text-blue-700 dark:text-blue-300">
                                                            {{ $loan->requested_duration_weeks }} {{ $loan->requested_duration_weeks == 1 ? 'Woche' : 'Wochen' }}
                                                        </p>
                                                    </div>
                                                @endif
                                                
                                                @if($loan->contact_info)
                                                    <div class="sm:col-span-2">
                                                        <span class="font-medium text-blue-800 dark:text-blue-200">📞 Kontakt:</span>
                                                        <p class="text-blue-700 dark:text-blue-300 break-all">{{ $loan->contact_info }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Lender's Response -->
                                    @if($loan->lender_response)
                                        <div class="bg-green-50 dark:bg-green-900/20 p-3 sm:p-4 rounded-lg mb-3 sm:mb-4">
                                            <h4 class="text-sm sm:text-base font-medium text-green-900 dark:text-green-100 mb-2 sm:mb-3">
                                                ✅ Antwort von {{ $loan->lender->name }}
                                                @if($loan->responded_at)
                                                    <span class="block sm:inline text-xs sm:text-sm text-green-600 dark:text-green-400">
                                                        ({{ $loan->responded_at->format('d.m.Y H:i') }})
                                                    </span>
                                                @endif
                                            </h4>
                                            <p class="text-xs sm:text-sm text-green-700 dark:text-green-300 break-words">{{ $loan->lender_response }}</p>
                                        </div>
                                    @endif
                                    
                                    <!-- Response Form for Lender -->
                                    @if($loan->lender_id === auth()->id() && $loan->status === 'angefragt' && !$loan->lender_response)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 rounded-lg">
                                            <h4 class="text-sm sm:text-base font-medium text-gray-900 dark:text-gray-100 mb-2 sm:mb-3">
                                                📝 Auf Anfrage antworten
                                            </h4>
                                            <form method="POST" action="{{ route('loans.update', $loan) }}" class="space-y-3 sm:space-y-4">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="action" value="respond">
                                                
                                                <div>
                                                    <label for="lender_response" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">
                                                        Ihre Nachricht an {{ $loan->borrower->name }}
                                                    </label>
                                                    <textarea name="lender_response" id="lender_response" rows="3" 
                                                              placeholder="Hallo! Vielen Dank für Ihr Interesse an meinem Buch..."
                                                              class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:text-gray-100 min-h-[44px]"
                                                              required></textarea>
                                                </div>
                                                
                                                <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:space-x-2">
                                                    <button type="submit" name="final_action" value="approve"
                                                            class="w-full sm:flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 rounded transition-colors text-xs sm:text-sm min-h-[44px]">
                                                        ✅ Genehmigen & Antworten
                                                    </button>
                                                    <button type="submit" name="final_action" value="deny"
                                                            class="w-full sm:flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded transition-colors text-xs sm:text-sm min-h-[44px]">
                                                        ❌ Ablehnen & Antworten
                                                    </button>
                                                    <button type="submit" name="final_action" value="respond_only"
                                                            class="w-full sm:flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded transition-colors text-xs sm:text-sm min-h-[44px]">
                                                        💬 Nur Antworten
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                                <!-- Notes -->
                                @if($loan->notes)
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 sm:pt-6">
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            📋 Zusätzliche Notizen
                                        </h3>
                                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-3 sm:p-4 rounded break-words">
                                            {{ $loan->notes }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Action Buttons - Mobile optimized -->
                                <div class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 pt-4 sm:pt-6 border-t border-gray-200 dark:border-gray-700">
                                    @if($loan->lender_id === auth()->id() && $loan->status === 'angefragt')
                                        <!-- Lender actions for pending requests -->
                                        <form method="POST" action="{{ route('loans.update', $loan) }}" class="w-full sm:w-auto">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" 
                                                    class="w-full sm:w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base min-h-[44px]">
                                                Genehmigen
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{ route('loans.update', $loan) }}" class="w-full sm:w-auto">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="deny">
                                            <button type="submit" 
                                                    class="w-full sm:w-auto bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base min-h-[44px]">
                                                Ablehnen
                                            </button>
                                        </form>
                                    @endif

                                    @if($loan->borrower_id === auth()->id() && $loan->status === 'angefragt')
                                        <!-- Borrower cancel action for pending requests -->
                                        <form method="POST" action="{{ route('loans.update', $loan) }}" class="w-full sm:w-auto">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="cancel">
                                            <button type="submit" 
                                                    class="w-full sm:w-auto bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base min-h-[44px]"
                                                    onclick="return confirm('Möchten Sie Ihren Ausleihantrag wirklich stornieren?')">
                                                Antrag stornieren
                                            </button>
                                        </form>
                                    @endif

                                    @if($loan->borrower_id === auth()->id() && $loan->status === 'aktiv')
                                        <!-- Borrower return action -->
                                        <form method="POST" action="{{ route('loans.update', $loan) }}" class="w-full sm:w-auto">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="return">
                                            <button type="submit" 
                                                    class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm sm:text-base min-h-[44px]"
                                                    onclick="return confirm('Möchten Sie dieses Buch wirklich zurückgeben?')">
                                                Buch zurückgeben
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('loans.index') }}" 
                                       class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-center text-sm sm:text-base min-h-[44px] flex items-center justify-center">
                                        Zurück zu Ausleihen
                                    </a>
                                    
                                    <a href="{{ route('books.show', $loan->book) }}" 
                                       class="w-full sm:w-auto bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-center text-sm sm:text-base min-h-[44px] flex items-center justify-center">
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