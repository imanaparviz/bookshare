<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3 hover:scale-105 transition-transform duration-200">
                        <div class="text-3xl">❤️</div>
                        <span class="text-xl font-bold text-gray-800 dark:text-gray-200 hidden md:block">BookShare</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center space-x-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')" class="flex items-center space-x-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>{{ __('Meine Bücher') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('loans.index')" :active="request()->routeIs('loans.*')" class="flex items-center space-x-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>{{ __('Ausleihen') }}</span>
                    </x-nav-link>
                    
                    <!-- Messaging-Link mit Unread-Badge -->
                    <x-nav-link :href="route('conversations.index')" :active="request()->routeIs('conversations.*')" class="flex items-center space-x-2 relative">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>{{ __('Nachrichten') }}</span>
                        @if(auth()->user()->hasUnreadMessages())
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ auth()->user()->getUnreadMessagesCount() }}
                            </span>
                        @endif
                    </x-nav-link>
                </div>
            </div>

            <!-- User Area -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Notifications Bell -->
                @php
                    // Anfragen an mich (als Lender) - Bücher die andere von mir ausleihen wollen
                    $lenderRequestsCount = \App\Models\Loan::where('lender_id', Auth::id())
                        ->where('status', \App\Models\Loan::STATUS_ANGEFRAGT)
                        ->count();
                    
                    // Meine Anfragen (als Borrower) - Bücher die ich ausleihen möchte/habe
                    $borrowerRequestsCount = \App\Models\Loan::where('borrower_id', Auth::id())
                        ->whereIn('status', [\App\Models\Loan::STATUS_ANGEFRAGT, \App\Models\Loan::STATUS_AKTIV])
                        ->count();
                    
                    // Gesamtanzahl aller aktiven Loan-Aktivitäten
                    $totalLoansCount = $lenderRequestsCount + $borrowerRequestsCount;
                @endphp
                <a href="{{ route('loans.index') }}" class="relative bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 hover:border-yellow-400 dark:hover:border-yellow-400 p-3 rounded-xl shadow-md hover:shadow-lg text-gray-700 dark:text-gray-200 hover:text-yellow-600 dark:hover:text-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50 transition-all duration-200 transform hover:scale-105">
                    <span class="sr-only">Alle Ausleihaktivitäten anzeigen</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    <!-- Notification count (if any loans exist) -->
                    @if($totalLoansCount > 0)
                        <span class="absolute -top-1 -right-1 block h-4 w-4 rounded-full bg-red-500 border-2 border-white dark:border-gray-800 text-xs text-white font-bold flex items-center justify-center animate-pulse">{{ $totalLoansCount }}</span>
                    @endif
                </a>

                <!-- User Profile Trigger for Sidebar -->
                <div class="sidebar-user-trigger flex items-center max-w-xs bg-white dark:bg-gray-800 rounded-full hover:shadow-md transition-all duration-200 cursor-pointer">
                    <div class="flex items-center px-3 py-2">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600" />
                            @else
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center text-white font-semibold text-lg border-2 border-gray-200 dark:border-gray-600">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- User Info -->
                        <div class="ml-3 hidden lg:block">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ Auth::user()->name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Online
                            </div>
                        </div>
                        
                        <!-- Sidebar Arrow -->
                        <div class="ml-2">
                            <svg class="fill-current h-4 w-4 text-gray-400 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Notifications and Hamburger -->
            <div class="flex items-center space-x-2 sm:hidden">
                <!-- Mobile Notifications Bell -->
                @php
                    // Anfragen an mich (als Lender) - Bücher die andere von mir ausleihen wollen
                    $lenderRequestsCount = \App\Models\Loan::where('lender_id', Auth::id())
                        ->where('status', \App\Models\Loan::STATUS_ANGEFRAGT)
                        ->count();
                    
                    // Meine Anfragen (als Borrower) - Bücher die ich ausleihen möchte/habe
                    $borrowerRequestsCount = \App\Models\Loan::where('borrower_id', Auth::id())
                        ->whereIn('status', [\App\Models\Loan::STATUS_ANGEFRAGT, \App\Models\Loan::STATUS_AKTIV])
                        ->count();
                    
                    // Gesamtanzahl aller aktiven Loan-Aktivitäten
                    $totalLoansCount = $lenderRequestsCount + $borrowerRequestsCount;
                @endphp
                <a href="{{ route('loans.index') }}" class="relative bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 hover:border-yellow-400 dark:hover:border-yellow-400 p-2 rounded-lg shadow-md text-gray-700 dark:text-gray-200 hover:text-yellow-600 dark:hover:text-yellow-400 transition-all duration-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    @if($totalLoansCount > 0)
                        <span class="absolute -top-1 -right-1 block h-4 w-4 rounded-full bg-red-500 border-2 border-white dark:border-gray-800 text-xs text-white font-bold flex items-center justify-center animate-pulse">{{ $totalLoansCount }}</span>
                    @endif
                </a>
                
                <!-- Hamburger -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center pl-3">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
                </svg>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')" class="flex items-center pl-3">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                {{ __('Meine Bücher') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('loans.index')" :active="request()->routeIs('loans.*')" class="flex items-center pl-3">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                {{ __('Ausleihen') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive User Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900">
            <!-- User Info Header -->
            <div class="px-4 pb-3">
                <div class="flex items-center">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow" />
                    @else
                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center text-white font-semibold text-lg border-2 border-white dark:border-gray-600 shadow">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="ml-3">
                        <div class="font-semibold text-base text-gray-800 dark:text-gray-200">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ Auth::user()->email }}
                        </div>
                        <div class="text-xs text-green-600 dark:text-green-400 font-medium">
                            ● Online
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Items -->
            <div class="space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center pl-3">
                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Mein Profil') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('books.index')" class="flex items-center pl-3">
                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    {{ __('Meine Bücher') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('loans.index')" class="flex items-center pl-3">
                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    {{ __('Ausleihen') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('ratings.user', Auth::user())" class="flex items-center pl-3">
                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    {{ __('Meine Bewertungen') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="#" class="flex items-center pl-3">
                    <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('Einstellungen') }}
                </x-responsive-nav-link>

                <!-- Logout -->
                <div class="border-t border-gray-300 dark:border-gray-600 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="flex items-center pl-3 text-red-600 hover:text-red-900 hover:bg-red-50 dark:hover:bg-red-900 dark:hover:text-red-100">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Abmelden') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
