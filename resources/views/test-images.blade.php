<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookShare - Bilder Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">BookShare - Bilder Test</h1>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @php
                $books = \App\Models\Book::all();
            @endphp
            
            @foreach($books as $book)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="h-64 bg-gray-200 flex items-center justify-center">
                        @if($book->image_path && file_exists(public_path($book->image_path)))
                            <img src="{{ asset($book->image_path) }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-4">
                                <div class="text-gray-500 mb-2 text-2xl">BUCH</div>
                                <div class="text-xs text-gray-600">Kein Bild</div>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-sm mb-1 line-clamp-2">{{ $book->title }}</h3>
                        <p class="text-xs text-gray-600 mb-2">{{ $book->author }}</p>
                        <div class="text-xs">
                            @if($book->image_path)
                                <span class="text-green-600">Bild vorhanden</span>
                            @else
                                <span class="text-red-600">Kein Bild</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('welcome') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Zurück zur Homepage
            </a>
        </div>
    </div>
</body>
</html> 