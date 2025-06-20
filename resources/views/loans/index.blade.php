<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meine Ausleihen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <!-- Borrowed Books Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Von mir ausgeliehene Bücher</h3>
                    
                    @if($borrowedBooks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Buch
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Verleiher
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Fälligkeitsdatum
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aktionen
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($borrowedBooks as $loan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($loan->book->image_path)
                                                        @if(str_starts_with($loan->book->image_path, 'images/'))
                                                            {{-- Static seeded images --}}
                                                            <img class="h-10 w-8 rounded object-cover mr-3" 
                                                                 src="{{ asset($loan->book->image_path) }}" 
                                                                 alt="{{ $loan->book->title }}">
                                                        @else
                                                            {{-- User uploaded images --}}
                                                            <img class="h-10 w-8 rounded object-cover mr-3" 
                                                                 src="{{ asset('storage/' . $loan->book->image_path) }}" 
                                                                 alt="{{ $loan->book->title }}">
                                                        @endif
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $loan->book->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $loan->book->author }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $loan->lender->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($loan->status === 'aktiv') bg-green-100 text-green-800
                                                    @elseif($loan->status === 'angefragt') bg-yellow-100 text-yellow-800
                                                    @elseif($loan->status === 'abgelehnt') bg-red-100 text-red-800
                                                    @elseif($loan->status === 'storniert') bg-orange-100 text-orange-800
                                                    @elseif($loan->status === 'zurückgegeben') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($loan->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                @if($loan->due_date)
                                                    {{ $loan->due_date->format('d.m.Y') }}
                                                    @if($loan->is_overdue)
                                                        <span class="text-red-500 font-bold">(Überfällig)</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($loan->status === 'angefragt')
                                                    <form method="POST" action="{{ route('loans.update', $loan) }}" class="inline mr-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="action" value="cancel">
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                                onclick="return confirm('Möchten Sie Ihren Ausleihantrag wirklich stornieren?')">
                                                            Stornieren
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($loan->status === 'aktiv')
                                                    <form method="POST" action="{{ route('loans.update', $loan) }}" class="inline mr-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="action" value="return">
                                                        <button type="submit" 
                                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                                onclick="return confirm('Möchten Sie dieses Buch wirklich zurückgeben?')">
                                                            Zurückgeben
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('loans.show', $loan) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Sie haben derzeit keine Bücher ausgeliehen.</p>
                    @endif
                </div>
            </div>

            <!-- Lent Books Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Von mir verliehene Bücher</h3>
                    
                    @if($lentBooks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Buch
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Ausleiher
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Ausgeliehen am
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aktionen
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($lentBooks as $loan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($loan->book->image_path)
                                                        @if(str_starts_with($loan->book->image_path, 'images/'))
                                                            {{-- Static seeded images --}}
                                                            <img class="h-10 w-8 rounded object-cover mr-3" 
                                                                 src="{{ asset($loan->book->image_path) }}" 
                                                                 alt="{{ $loan->book->title }}">
                                                        @else
                                                            {{-- User uploaded images --}}
                                                            <img class="h-10 w-8 rounded object-cover mr-3" 
                                                                 src="{{ asset('storage/' . $loan->book->image_path) }}" 
                                                                 alt="{{ $loan->book->title }}">
                                                        @endif
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $loan->book->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $loan->book->author }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $loan->borrower->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($loan->status === 'aktiv') bg-green-100 text-green-800
                                                    @elseif($loan->status === 'angefragt') bg-yellow-100 text-yellow-800
                                                    @elseif($loan->status === 'abgelehnt') bg-red-100 text-red-800
                                                    @elseif($loan->status === 'storniert') bg-orange-100 text-orange-800
                                                    @elseif($loan->status === 'zurückgegeben') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($loan->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $loan->loan_date ? $loan->loan_date->format('d.m.Y') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($loan->status === 'angefragt')
                                                    <form method="POST" action="{{ route('loans.update', $loan) }}" class="inline mr-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="action" value="approve">
                                                        <button type="submit" 
                                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                            Genehmigen
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('loans.update', $loan) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="action" value="deny">
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Ablehnen
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('loans.show', $loan) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 ml-2">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Sie haben derzeit keine Bücher verliehen.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 