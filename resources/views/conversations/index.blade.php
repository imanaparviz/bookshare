<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                💬 Meine Gespräche
            </h2>
            @if($conversations->count() > 0)
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $conversations->count() }} {{ $conversations->count() === 1 ? 'Gespräch' : 'Gespräche' }}
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($conversations->count() > 0)
                <!-- Conversations List -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($conversations as $conversation)
                            @php
                                $otherParticipant = $conversation->getOtherParticipant(auth()->user());
                                $latestMessage = $conversation->latestMessage->first();
                                $unreadCount = $conversation->getUnreadCount(auth()->user());
                            @endphp
                            
                            <div class="p-4 sm:p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-start space-x-4">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        @if($otherParticipant->avatar)
                                            <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600" 
                                                 src="{{ asset('storage/' . $otherParticipant->avatar) }}" 
                                                 alt="{{ $otherParticipant->name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold text-lg border-2 border-gray-200 dark:border-gray-600">
                                                {{ strtoupper(substr($otherParticipant->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        
                                        <!-- Online Status Indicator -->
                                        @if($otherParticipant->isOnline())
                                            <div class="absolute ml-8 -mt-3 w-4 h-4 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <!-- Name and Status -->
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $otherParticipant->name }}
                                                    </h3>
                                                    @if($otherParticipant->isOnline())
                                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                            Online
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $otherParticipant->online_status }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Book Info -->
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="font-medium">{{ $conversation->loan->book->title }}</span>
                                                        <span class="text-xs ml-1 text-gray-500">von {{ $conversation->loan->book->author }}</span>
                                                    </div>
                                                </div>

                                                <!-- Latest Message -->
                                                @if($latestMessage)
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                                                <span class="font-medium">
                                                                    {{ $latestMessage->sender_id === auth()->id() ? 'Sie:' : $otherParticipant->name . ':' }}
                                                                </span>
                                                                {{ $latestMessage->formatted_content }}
                                                            </p>
                                                            <div class="flex items-center space-x-2 mt-1">
                                                                <span class="text-xs text-gray-500">
                                                                    {{ $latestMessage->created_at->diffForHumans() }}
                                                                </span>
                                                                @if($otherParticipant->response_time)
                                                                    <span class="text-xs text-gray-400">•</span>
                                                                    <span class="text-xs text-gray-500">
                                                                        {{ $otherParticipant->response_time }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center space-x-3 ml-4">
                                                @if($unreadCount > 0)
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-500 text-white rounded-full">
                                                        {{ $unreadCount }}
                                                    </span>
                                                @endif
                                                
                                                <a href="{{ route('conversations.show', $conversation) }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                    </svg>
                                                    Öffnen
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @else
                <!-- Modern Empty State -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-12 sm:px-12 sm:py-16 text-center">
                        <div class="mx-auto max-w-md">
                            <!-- Icon -->
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            
                            <!-- Content -->
                            <div class="mt-6">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                    Noch keine Gespräche vorhanden
                                </h3>
                                <p class="mt-3 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                    Gespräche werden automatisch erstellt, wenn Sie eine Buchausleihe anfragen oder jemand eines Ihrer Bücher ausleihen möchte. Entdecken Sie Bücher und starten Sie Ihre erste Unterhaltung!
                                </p>
                            </div>
                            
                            <!-- Actions -->
                            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('books.index') }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    Bücher entdecken
                                </a>
                                <a href="{{ route('welcome') }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    Zurück zur Startseite
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Grid - Only show if there are conversations -->
            @if($conversations->count() > 0)
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktive Gespräche</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $conversations->where('is_active', true)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ungelesene Nachrichten</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->getUnreadMessagesCount() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ihr Status</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ auth()->user()->isOnline() ? 'Online' : 'Offline' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 