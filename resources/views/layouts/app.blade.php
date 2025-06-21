<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Custom styles for the collapsible sidebar - Dark Mode Development */
            .user-sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
                backdrop-filter: blur(15px);
                /* Dark background matching website theme */
                background: rgba(17, 24, 39, 0.95); /* gray-900 like main app */
                border-left: 1px solid rgba(75, 85, 99, 0.2); /* gray-600 */
            }
            
            .user-sidebar:hover,
            .user-sidebar.show {
                transform: translateX(0);
                box-shadow: -20px 0 60px rgba(0, 0, 0, 0.5);
            }
            

            
            .user-sidebar ul li {
                transition: all 0.3s ease;
            }
            
            .user-sidebar ul li:hover {
                transform: translateX(8px);
                background: rgba(59, 130, 246, 0.15); /* blue hover */
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
            }
            
            .profile-avatar {
                transition: transform 0.3s ease;
            }
            
            .profile-avatar:hover {
                transform: scale(1.1) rotate(5deg);
            }
            
            /* All text colors light for dark mode development */
            .user-sidebar h3,
            .user-sidebar h4,
            .user-sidebar .text-lg {
                color: rgb(248, 250, 252) !important; /* slate-50 - very light */
            }
            
            .user-sidebar .text-sm,
            .user-sidebar .text-xs,
            .user-sidebar span {
                color: rgb(203, 213, 225) !important; /* slate-300 - light */
            }
            
            .user-sidebar a {
                color: rgb(226, 232, 240) !important; /* slate-200 - light */
            }
            
            .user-sidebar a:hover {
                color: rgb(96, 165, 250) !important; /* blue-400 - bright blue */
            }
            
            /* Stat cards with dark background */
            .user-sidebar .bg-slate-700\/60 {
                background: rgba(31, 41, 55, 0.7) !important; /* gray-800 */
                border-color: rgba(75, 85, 99, 0.3) !important; /* gray-600 */
            }
            
            /* Header with dark gradient */
            .user-sidebar .bg-gradient-to-r {
                background: linear-gradient(to right, rgb(31, 41, 55), rgb(17, 24, 39)) !important; /* gray-800 to gray-900 */
            }
            
            /* Borders with dark theme */
            .user-sidebar .border-slate-700\/30 {
                border-color: rgba(75, 85, 99, 0.3) !important; /* gray-600 */
            }
            
            /* Status indicator colors */
            .user-sidebar .text-blue-400,
            .user-sidebar .text-emerald-400,
            .user-sidebar .text-amber-400 {
                color: rgb(96, 165, 250) !important; /* blue-400 */
            }
            
            .user-sidebar .text-emerald-400 {
                color: rgb(52, 211, 153) !important; /* emerald-400 */
            }
            
            .user-sidebar .text-amber-400 {
                color: rgb(251, 191, 36) !important; /* amber-400 */
            }
            
            /* Make sure all text elements are light */
            .user-sidebar * {
                color: rgb(203, 213, 225) !important; /* default light color */
            }
            
            /* Override for specific bright elements */
            .user-sidebar h3,
            .user-sidebar h4 {
                color: rgb(248, 250, 252) !important; /* brightest for headings */
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 relative">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative">
                {{ $slot }}
            </main>

            <!-- Right Sidebar - User Profile -->
            <div class="user-sidebar fixed top-0 right-0 h-full w-84 shadow-2xl z-50">
                <!-- Sidebar Content -->
                <div class="h-full flex flex-col">
                    <!-- Header -->
                    <div class="p-6 border-b border-slate-700/30 bg-gradient-to-r from-slate-700 to-slate-800">
                        <div class="flex items-center space-x-4">
                            <div class="profile-avatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="h-16 w-16 rounded-full object-cover border-4 border-white shadow-lg" />
                                @else
                                    <div class="h-16 w-16 rounded-full bg-slate-600 flex items-center justify-center text-slate-200 font-bold text-xl border-4 border-slate-500 shadow-lg">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                                                         <div class="text-slate-100">
                                <h3 class="text-lg font-semibold">{{ Auth::user()->name }}</h3>
                                <p class="text-slate-300 text-sm">{{ Auth::user()->email }}</p>
                                <div class="flex items-center mt-1">
                                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse mr-2"></div>
                                    <span class="text-xs text-slate-300">Online</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Stats -->
                    <div class="p-4 bg-slate-800/50 border-b border-slate-700/30">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            @php
                                $userBooksCount = \App\Models\Book::where('owner_id', Auth::id())->count();
                                $userLoansCount = \App\Models\Loan::where('borrower_id', Auth::id())->count();
                                $userRatingsCount = \App\Models\Rating::where('user_id', Auth::id())->count();
                            @endphp
                            <div class="bg-slate-700/60 rounded-lg p-3 shadow-sm border border-slate-600/30">
                                <div class="text-2xl font-bold text-blue-400">{{ $userBooksCount }}</div>
                                <div class="text-xs text-slate-300">Bücher</div>
                            </div>
                            <div class="bg-slate-700/60 rounded-lg p-3 shadow-sm border border-slate-600/30">
                                <div class="text-2xl font-bold text-emerald-400">{{ $userLoansCount }}</div>
                                <div class="text-xs text-slate-300">Ausleihen</div>
                            </div>
                            <div class="bg-slate-700/60 rounded-lg p-3 shadow-sm border border-slate-600/30">
                                <div class="text-2xl font-bold text-amber-400">{{ $userRatingsCount }}</div>
                                <div class="text-xs text-slate-300">Bewertungen</div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="flex-1 p-4">
                        <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wide mb-4">Navigation</h4>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                                    <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
                                    </svg>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('books.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                                    <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span>Meine Bücher</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loans.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                                    <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <span>Ausleihen</span>
                                    @php
                                        $totalLoansCount = \App\Models\Loan::where('lender_id', Auth::id())
                                            ->where('status', \App\Models\Loan::STATUS_ANGEFRAGT)
                                            ->count() + \App\Models\Loan::where('borrower_id', Auth::id())
                                            ->whereIn('status', [\App\Models\Loan::STATUS_ANGEFRAGT, \App\Models\Loan::STATUS_AKTIV])
                                            ->count();
                                    @endphp
                                    @if($totalLoansCount > 0)
                                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full ml-auto">{{ $totalLoansCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('ratings.user') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                                    <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    <span>Bewertungen</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                                    <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Profil bearbeiten</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Settings Section -->
                        <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wide mb-4 mt-8">Einstellungen</h4>
                        <ul class="space-y-2">
                            <li>
                                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-slate-200 hover:bg-blue-500/20 hover:text-blue-300 transition-all duration-200 group">
                                    <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Allgemein</span>
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-all duration-200 group w-full text-left">
                                        <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Abmelden</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <!-- Footer -->
                   <!--  <div class="p-4 border-t border-slate-700/30">
                        <div class="text-center text-xs text-slate-400">
                            <p>&copy; {{ date('Y') }} BookShare</p>
                            <p class="mt-1">Made with ❤️</p>
                        </div>
                    </div> -->
                </div>
            </div>


        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.querySelector('.user-sidebar');
                const userProfileTrigger = document.querySelector('.sidebar-user-trigger');
                
                // Function to show sidebar
                function showSidebar() {
                    if (sidebar) {
                        sidebar.classList.add('show');
                    }
                }
                
                // Function to hide sidebar
                function hideSidebar() {
                    if (sidebar) {
                        setTimeout(() => {
                            if (!sidebar.matches(':hover') && 
                                !userProfileTrigger?.matches(':hover')) {
                                sidebar.classList.remove('show');
                            }
                        }, 100);
                    }
                }
                
                // Show sidebar on user profile trigger hover
                if (userProfileTrigger) {
                    userProfileTrigger.addEventListener('mouseenter', showSidebar);
                    userProfileTrigger.addEventListener('mouseleave', hideSidebar);
                }
                
                // Keep sidebar open when hovering over it
                if (sidebar) {
                    sidebar.addEventListener('mouseenter', showSidebar);
                    sidebar.addEventListener('mouseleave', hideSidebar);
                }
            });
        </script>
    </body>
</html>
