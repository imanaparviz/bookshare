<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <svg class="w-16 h-16 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Werde Teil der Community</h2>
                <p class="text-purple-200">Erstelle dein Konto und teile deine Bücher</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 shadow-2xl border border-white/20">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" class="text-white font-medium" />
                        <x-text-input id="name" 
                                      class="mt-2 block w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent backdrop-blur-sm" 
                                      type="text" 
                                      name="name" 
                                      :value="old('name')" 
                                      required 
                                      autofocus 
                                      autocomplete="name"
                                      placeholder="Dein vollständiger Name"/>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('E-Mail')" class="text-white font-medium" />
                        <x-text-input id="email" 
                                      class="mt-2 block w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent backdrop-blur-sm" 
                                      type="email" 
                                      name="email" 
                                      :value="old('email')" 
                                      required 
                                      autocomplete="username"
                                      placeholder="deine@email.de"/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Passwort')" class="text-white font-medium" />
                        <x-text-input id="password" 
                                      class="mt-2 block w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent backdrop-blur-sm"
                                      type="password"
                                      name="password"
                                      required 
                                      autocomplete="new-password"
                                      placeholder="Mindestens 8 Zeichen"/>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-300" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Passwort bestätigen')" class="text-white font-medium" />
                        <x-text-input id="password_confirmation" 
                                      class="mt-2 block w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent backdrop-blur-sm"
                                      type="password"
                                      name="password_confirmation" 
                                      required 
                                      autocomplete="new-password"
                                      placeholder="Passwort wiederholen"/>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-pink-300" />
                    </div>

                    <div>
                        <x-primary-button class="w-full justify-center py-3 text-lg font-semibold bg-purple-600 hover:bg-purple-700 focus:ring-purple-500 rounded-lg transition-all transform hover:scale-105">
                            {{ __('Registrieren') }}
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-white/70">
                        Bereits registriert? 
                        <a href="{{ route('login') }}" class="text-purple-300 hover:text-purple-200 font-medium transition-colors">
                            Hier anmelden
                        </a>
                    </p>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('welcome') }}" class="text-purple-300 hover:text-purple-200 transition-colors">
                    ← Zurück zur Homepage
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
