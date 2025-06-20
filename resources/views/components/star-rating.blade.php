@props(['rating' => 0, 'size' => 'md', 'readonly' => false, 'name' => 'rating'])

@php
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5', 
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8'
    ];
    $starSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

@if($readonly)
    <!-- Read-only stars display -->
    <div class="flex items-center gap-1">
        @for($i = 1; $i <= 5; $i++)
            <svg class="{{ $starSize }}" 
                 fill="currentColor" 
                 viewBox="0 0 20 20"
                 style="color: {{ $i <= $rating ? '#fbbf24' : '#d1d5db' }};">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        @endfor
    </div>
@else
    <!-- Interactive stars rating -->
    <div class="flex items-center gap-1" 
         x-data="{ 
            currentRating: {{ $rating }}, 
            hoverRating: 0
         }">
        
        @for($i = 1; $i <= 5; $i++)
            <button type="button" 
                    @mouseenter="hoverRating = {{ $i }}"
                    @mouseleave="hoverRating = 0"
                    @click="currentRating = {{ $i }}; $refs.hiddenInput.value = {{ $i }}; $dispatch('rating-changed', { rating: {{ $i }} })"
                    class="{{ $starSize }} transition-all duration-200 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50 rounded cursor-pointer">
                <svg viewBox="0 0 20 20" 
                     class="w-full h-full" 
                     fill="currentColor"
                     :style="(hoverRating >= {{ $i }} || (hoverRating === 0 && currentRating >= {{ $i }})) ? 'color: #fbbf24' : 'color: #d1d5db'">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </button>
        @endfor
        
        <!-- Hidden input for forms -->
        <input type="hidden" 
               name="{{ $name }}" 
               x-ref="hiddenInput"
               :value="currentRating" 
               value="{{ $rating }}">
        
        <!-- Rating text -->
        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300" 
              x-show="currentRating > 0"
              x-text="currentRating + '/5'">
        </span>
        
        <!-- Clear button -->
        <button type="button" 
                @click="currentRating = 0; $refs.hiddenInput.value = 0"
                x-show="currentRating > 0"
                class="ml-2 text-xs text-gray-500 hover:text-red-500 transition-colors">
            Zurücksetzen
        </button>
    </div>
@endif 