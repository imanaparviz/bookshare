<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Zurück-Button -->
                <a href="{{ route('conversations.index') }}" 
                   class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                
                <!-- Gesprächspartner-Info -->
                <div class="flex items-center space-x-3">
                    @if($otherParticipant->avatar)
                        <img class="h-10 w-10 rounded-full object-cover" 
                             src="{{ asset('storage/' . $otherParticipant->avatar) }}" 
                             alt="{{ $otherParticipant->name }}">
                    @else
                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($otherParticipant->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div>
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            {{ $otherParticipant->name }}
                        </h2>
                        <div class="flex items-center space-x-2 text-sm">
                            @if($otherParticipant->isOnline())
                                <span class="text-green-600 dark:text-green-400">🟢 Online</span>
                            @else
                                <span class="text-gray-500">{{ $otherParticipant->online_status }}</span>
                            @endif
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-500">{{ $otherParticipant->response_time }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buch-Info -->
            <div class="hidden md:flex items-center space-x-2 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                <span class="text-sm">📚</span>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $conversation->loan->book->title }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="h-screen flex flex-col">
        <div class="flex-1 overflow-hidden">
            <!-- Chat-Container -->
            <div class="h-full flex flex-col max-w-4xl mx-auto">
                
                <!-- Nachrichten-Bereich -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
                    
                    <!-- Buch-Context-Card (nur einmal am Anfang) -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-center space-x-3">
                            @if($conversation->loan->book->image_path && file_exists(public_path('storage/' . $conversation->loan->book->image_path)))
                                <img src="{{ asset('storage/' . $conversation->loan->book->image_path) }}" 
                                     alt="{{ $conversation->loan->book->title }}" 
                                     class="w-12 h-16 object-cover rounded">
                            @else
                                <div class="w-12 h-16 bg-gray-300 dark:bg-gray-600 rounded flex items-center justify-center">
                                    <span class="text-gray-500 text-xs">📚</span>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $conversation->loan->book->title }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    von {{ $conversation->loan->book->author }}
                                </p>
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span>Status: 
                                        <span class="font-medium">{{ ucfirst($conversation->loan->status) }}</span>
                                    </span>
                                    @if($conversation->loan->due_date)
                                        <span>Rückgabe: {{ $conversation->loan->due_date->format('d.m.Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nachrichten -->
                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md">
                                
                                <!-- System-Nachrichten -->
                                @if($message->isSystemMessage())
                                    <div class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 px-4 py-2 rounded-lg text-center text-sm">
                                        {{ $message->formatted_content }}
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $message->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                
                                <!-- Normale Nachrichten -->
                                @else
                                    <div class="flex items-end space-x-2 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                        <!-- Avatar (nur bei anderen) -->
                                        @if($message->sender_id !== auth()->id())
                                            @if($otherParticipant->avatar)
                                                <img class="h-6 w-6 rounded-full object-cover" 
                                                     src="{{ asset('storage/' . $otherParticipant->avatar) }}" 
                                                     alt="{{ $otherParticipant->name }}">
                                            @else
                                                <div class="h-6 w-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs">
                                                    {{ strtoupper(substr($otherParticipant->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        @endif

                                        <!-- Nachrichten-Bubble -->
                                        <div class="px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() 
                                            ? 'bg-blue-600 text-white' 
                                            : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100' }}">
                                            
                                            <p class="text-sm">{{ $message->content }}</p>
                                            
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                                                    {{ $message->created_at->format('H:i') }}
                                                </span>
                                                
                                                <!-- Lese-Status (nur eigene Nachrichten) -->
                                                @if($message->sender_id === auth()->id())
                                                    <span class="text-xs text-blue-100 ml-2">
                                                        {{ $message->is_read ? '✓✓' : '✓' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            <p>Noch keine Nachrichten. Starten Sie das Gespräch!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Nachrichten-Input -->
                <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4">
                    <div class="max-w-4xl mx-auto">
                        
                        <!-- Schnellnachrichten -->
                        <div class="mb-4 flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('conversations.quick', $conversation) }}" class="inline">
                                @csrf
                                <input type="hidden" name="template" value="pickup_ready">
                                <button type="submit" class="text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-full transition-colors">
                                    📚 Buch ist bereit
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('conversations.quick', $conversation) }}" class="inline">
                                @csrf
                                <input type="hidden" name="template" value="pickup_confirmed">
                                <button type="submit" class="text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-full transition-colors">
                                    ✅ Abholung bestätigt
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('conversations.quick', $conversation) }}" class="inline">
                                @csrf
                                <input type="hidden" name="template" value="thanks">
                                <button type="submit" class="text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-full transition-colors">
                                    🙏 Danke
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('conversations.quick', $conversation) }}" class="inline">
                                @csrf
                                <input type="hidden" name="template" value="delay_request">
                                <button type="submit" class="text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-full transition-colors">
                                    ⏰ Verlängerung anfragen
                                </button>
                            </form>
                        </div>

                        <!-- Nachricht senden -->
                        <form method="POST" action="{{ route('conversations.send', $conversation) }}" class="flex space-x-4">
                            @csrf
                            <div class="flex-1">
                                <textarea name="content" 
                                         rows="2" 
                                         placeholder="Nachricht eingeben..." 
                                         class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg resize-none focus:ring-blue-500 focus:border-blue-500"
                                         required></textarea>
                            </div>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors self-end">
                                💬 Senden
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-scroll Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messages-container');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            // Auto-refresh alle 30 Sekunden für neue Nachrichten
            setInterval(function() {
                // Hier könnte AJAX-Update für neue Nachrichten implementiert werden
            }, 30000);
        });
    </script>
</x-app-layout> 