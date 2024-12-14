@extends('layouts.admin')

@section('title', 'Szczegóły opinii')

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- Header with back button --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reviews.index') }}" 
                   class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Powrót do listy
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Szczegóły opinii</h1>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                {{ $review->status === 'approved' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800' : '' }}
                {{ $review->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800' : '' }}
                {{ $review->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800' : '' }}">
                {{ ucfirst($review->status) }}
            </span>
        </div>

        <div class="grid gap-6">
            {{-- Main info card --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="grid md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-200 dark:divide-gray-700">
                    {{-- Review info --}}
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Informacje o opinii
                        </h2>
                        <dl class="space-y-4">
                            <div class="flex items-center">
                                <dt class="w-1/3 flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">Ocena</dt>
                                <dd class="flex items-center">
                                    <span class="text-xl font-bold text-yellow-500 dark:text-yellow-400 mr-2">{{ $review->rating }}</span>
                                    <div class="flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" 
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </dd>
                            </div>
                            <div class="flex">
                                <dt class="w-1/3 flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">Data wizyty</dt>
                                <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $review->visit_date->format('d.m.Y') }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="w-1/3 flex-shrink-0 text-sm text-gray-500 dark:text-gray-400">Data dodania</dt>
                                <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $review->created_at->format('d.m.Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Location info --}}
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Miejsce
                        </h2>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nazwa</dt>
                                <dd>
                                    <a href="{{ route('admin.beer-spots.show', $review->beerSpot) }}" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                        {{ $review->beerSpot->name }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Adres</dt>
                                <dd class="text-gray-900 dark:text-gray-100">{{ $review->beerSpot->address }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Review content --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    Treść opinii
                </h2>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                    {{ $review->comment }}
                </p>
            </div>

            {{-- Author info --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Autor
                </h2>
                <dl class="grid md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nazwa użytkownika</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $review->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Email</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $review->user->email }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Action buttons --}}
            <div class="flex justify-end gap-3 mt-2">
                @if($review->status === 'pending')
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white rounded-lg transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Zatwierdź
                        </button>
                    </form>
                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-lg transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Odrzuć
                        </button>
                    </form>
                @endif
                <form action="{{ route('admin.reviews.destroy', $review) }}" 
                      method="POST" 
                      onsubmit="return confirm('Czy na pewno chcesz usunąć tę opinię?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 active:bg-gray-800 text-white rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Usuń
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection