<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <svg class="w-16 h-16 text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Willkommen zurück</h2>
                <p class="text-indigo-200">Melde dich an und entdecke neue Bücher</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 shadow-2xl border border-white/20">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('E-Mail')" class="text-white font-medium" />
                        <x-text-input id="email" 
                                      class="mt-2 block w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent backdrop-blur-sm" 
                                      type="email" 
                                      name="email" 
                                      :value="old('email')" 
                                      required 
                                      autofocus 
                                      autocomplete="username"
                                      placeholder="deine@email.de"/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Passwort')" class="text-white font-medium" />
                        <x-text-input id="password" 
                                      class="mt-2 block w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent backdrop-blur-sm"
                                      type="password"
                                      name="password"
                                      required 
                                      autocomplete="current-password"
                                      placeholder="••••••••"/>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   class="rounded border-white/30 bg-white/20 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-0" 
                                   name="remember">
                            <span class="ml-2 text-sm text-white">{{ __('Angemeldet bleiben') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-indigo-300 hover:text-indigo-200 transition-colors" href="{{ route('password.request') }}">
                                {{ __('Passwort vergessen?') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <x-primary-button class="w-full justify-center py-3 text-lg font-semibold bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 rounded-lg transition-all transform hover:scale-105">
                            {{ __('Anmelden') }}
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-white/70">
                        Noch kein Konto? 
                        <a href="{{ route('register') }}" class="text-indigo-300 hover:text-indigo-200 font-medium transition-colors">
                            Jetzt registrieren
                        </a>
                    </p>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('welcome') }}" class="text-indigo-300 hover:text-indigo-200 transition-colors">
                    ← Zurück zur Homepage
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
