@extends('layouts.admin')

@section('title', $beerSpot->name)

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-start">
        <div>
                <a href="{{ route('admin.beer-spots.index') }}" 
                   class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Powrót do listy
                </a>

            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">

                {{ $beerSpot->name }}
                @if($beerSpot->verified)
                    <span class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Zweryfikowany
                    </span>
                @endif
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Dodano: {{ $beerSpot->created_at->format('d.m.Y H:i') }}
                @if($beerSpot->updated_at->ne($beerSpot->created_at))
                    <span class="mx-2">•</span> Ostatnia aktualizacja: {{ $beerSpot->updated_at->format('d.m.Y H:i') }}
                @endif
            </p>
        </div>
        <div class="flex space-x-3">
            @if(!$beerSpot->verified)
                <form action="{{ route('admin.beer-spots.verify', $beerSpot) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-150">
                        Zweryfikuj
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.beer-spots.edit', $beerSpot) }}"
               class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-150">
                Edytuj
            </a>
            <form action="{{ route('admin.beer-spots.destroy', $beerSpot) }}" 
                  method="POST" 
                  onsubmit="return confirm('Czy na pewno chcesz usunąć ten punkt sprzedaży?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-150">
                    Usuń
                </button>
            </form>
        </div>
    </div>

    {{-- Status Bar --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</div>
            <div class="mt-1">
                <span class="px-3 py-1 rounded-full text-sm font-medium inline-flex items-center
                    {{ $beerSpot->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                    {{ $beerSpot->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                    {{ $beerSpot->status === 'inactive' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                    {{ ucfirst($beerSpot->status) }}
                </span>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Średnia ocena</div>
            <div class="mt-1 flex items-center">
                <span class="text-2xl font-bold text-yellow-500 dark:text-yellow-400">
                    {{ number_format($beerSpot->average_rating, 1) }}
                </span>
                <div class="flex ml-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($beerSpot->average_rating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Liczba piw</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                {{ $beerSpot->beers->count() }}
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Liczba opinii</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                {{ $beerSpot->reviews->count() }}
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Basic Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Informacje podstawowe</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="font-medium text-gray-700 dark:text-gray-300">Adres</dt>
                    <dd class="mt-1 text-gray-600 dark:text-gray-400">{{ $beerSpot->address }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-700 dark:text-gray-300">Współrzędne</dt>
                    <dd class="mt-1 text-gray-600 dark:text-gray-400 flex items-center">
                        <span>{{ number_format($beerSpot->latitude, 6) }}, {{ number_format($beerSpot->longitude, 6) }}</span>
                        <a href="https://www.google.com/maps?q={{ $beerSpot->latitude }},{{ $beerSpot->longitude }}" 
                           target="_blank"
                           class="ml-2 text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-700 dark:text-gray-300">Opis</dt>
                    <dd class="mt-1 text-gray-600 dark:text-gray-400">
                        {{ $beerSpot->description ?: 'Brak opisu' }}
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Opening Hours --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Godziny otwarcia</h2>
            <div class="space-y-3">
                @php
                    $days = [
                        'monday' => 'Poniedziałek',
                        'tuesday' => 'Wtorek',
                        'wednesday' => 'Środa',
                        'thursday' => 'Czwartek',
                        'friday' => 'Piątek',
                        'saturday' => 'Sobota',
                        'sunday' => 'Niedziela'
                    ];
                @endphp

                @foreach($days as $day => $dayName)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700 last:border-0">
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $dayName }}</span>
                        <span class="text-gray-600 dark:text-gray-400">
                            @if(isset($beerSpot->opening_hours[$day]['closed']) && $beerSpot->opening_hours[$day]['closed'])
                                <span class="text-red-600 dark:text-red-400">Zamknięte</span>
                            @else
                                @if(isset($beerSpot->opening_hours[$day]['open']) && isset($beerSpot->opening_hours[$day]['close']))
                                    {{ substr($beerSpot->opening_hours[$day]['open'], 0, 5) }} - {{ substr($beerSpot->opening_hours[$day]['close'], 0, 5) }}
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Brak danych</span>
                                @endif
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Beers List --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Piwa</h2>
                <a href="{{ route('admin.beers.create', ['beer_spot_id' => $beerSpot->id]) }}" 
                   class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm transition-colors duration-150">
                    Dodaj piwo
                </a>
            </div>
            <div class="space-y-3">
                @forelse($beerSpot->beers as $beer)
                    <div class="border border-gray-200 dark:border-gray-700 p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">{{ $beer->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.07-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $beer->type }}
                                    </span>
                                    @if($beer->alcohol_percentage)
                                        <span class="mx-2">•</span>
                                        <span>{{ number_format($beer->alcohol_percentage, 1) }}% ABV</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    {{ $beer->status === 'available' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                    {{ $beer->status === 'available' ? 'Dostępne' : 'Niedostępne' }}
                                </span>
                                <span class="font-bold text-lg text-gray-900 dark:text-white">
                                    {{ number_format($beer->price, 2) }} zł
                                </span>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end space-x-2">
                            <a href="{{ route('admin.beers.edit', $beer) }}" 
                               class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                Edytuj
                            </a>
                            <form action="{{ route('admin.beers.destroy', $beer) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Czy na pewno chcesz usunąć to piwo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    Usuń
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                        Brak dostępnych piw
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Reviews --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Opinie</h2>
            <div class="space-y-4">
                @forelse($beerSpot->reviews as $review)
                    <div class="border border-gray-200 dark:border-gray-700 p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $review->user->name }}</span>
                                    <div class="ml-2 flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Data wizyty: {{ $review->visit_date->format('d.m.Y') }}
                                    <span class="mx-2">•</span>
                                    Dodano: {{ $review->created_at->format('d.m.Y H:i') }}
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $review->status === 'approved' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                {{ $review->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                {{ $review->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>
                        <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $review->comment }}</p>
                        <div class="mt-3 flex justify-end space-x-2">
                            @if($review->status === 'pending')
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                                        Zatwierdź
                                    </button>
                                </form>
                                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                        Odrzuć
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.reviews.show', $review) }}" 
                               class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                Szczegóły
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                        Brak opinii
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection