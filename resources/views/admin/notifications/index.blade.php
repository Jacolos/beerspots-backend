@php
// resources/views/admin/notifications/index.blade.php
@endphp
@extends('layouts.admin')

@section('title', 'Powiadomienia')

@section('content')
<div class="space-y-6">
    <!-- Header z licznikiem nieprzeczytanych -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Powiadomienia</h1>
            @if($unreadCount = auth()->user()->unreadNotifications()->count())
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                    {{ $unreadCount }} nieprzeczytanych
                </span>
            @endif
        </div>
        
        <!-- Akcje masowe -->
        <div class="flex space-x-3">
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Oznacz wszystkie jako przeczytane
                </button>
            </form>
            <form action="{{ route('admin.notifications.destroy-all') }}" 
                  method="POST" 
                  onsubmit="return confirm('Czy na pewno chcesz usunąć wszystkie powiadomienia?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Usuń wszystkie
                </button>
            </form>
        </div>
    </div>

    <!-- Filtry -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="status" class="rounded-lg border-gray-300 dark:border-gray-600" onchange="this.form.submit()">
                <option value="">Wszystkie statusy</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Nieprzeczytane</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Przeczytane</option>
            </select>

            <select name="type" class="rounded-lg border-gray-300 dark:border-gray-600" onchange="this.form.submit()">
                <option value="">Wszystkie typy</option>
                <option value="NewBeerSpot" {{ request('type') == 'NewBeerSpot' ? 'selected' : '' }}>Nowy punkt</option>
                <option value="NewReview" {{ request('type') == 'NewReview' ? 'selected' : '' }}>Nowa recenzja</option>
                <option value="Welcome" {{ request('type') == 'Welcome' ? 'selected' : '' }}>Powitalne</option>
            </select>

            <select name="date" class="rounded-lg border-gray-300 dark:border-gray-600" onchange="this.form.submit()">
                <option value="">Cały okres</option>
                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Dzisiaj</option>
                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Ostatni tydzień</option>
                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Ostatni miesiąc</option>
            </select>
        </form>
    </div>

    <!-- Lista powiadomień -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 {{ !$notification->read_at ? 'border-l-4 border-blue-500' : '' }}">
                <div class="flex justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            @if(!$notification->read_at)
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            @endif
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ Str::title(str_replace('_', ' ', Str::afterLast($notification->type, '\\'))) }}
                            </h3>
                            <span class="text-sm text-gray-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <p class="mt-1 text-gray-600 dark:text-gray-300">
                            {{ $notification->data['message'] ?? 'Brak treści' }}
                        </p>
                        
                        @if(isset($notification->data['action_url']))
                            <a href="{{ $notification->data['action_url'] }}" 
                               class="mt-2 inline-block text-blue-600 hover:underline">
                                Zobacz szczegóły
                            </a>
                        @endif
                    </div>

                    <!-- Akcje dla pojedynczego powiadomienia -->
                    <div class="flex items-start space-x-2">
                        @if($notification->read_at)
                            <form action="{{ route('admin.notifications.mark-unread', $notification) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="text-blue-600 hover:text-blue-800" 
                                        title="Oznacz jako nieprzeczytane">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                                    </svg>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.notifications.mark-read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="text-blue-600 hover:text-blue-800" 
                                        title="Oznacz jako przeczytane">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.notifications.destroy', $notification) }}" 
                              method="POST" 
                              onsubmit="return confirm('Czy na pewno chcesz usunąć to powiadomienie?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-800" 
                                    title="Usuń">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Brak powiadomień</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Nie masz żadnych powiadomień do wyświetlenia.</p>
            </div>
        @endforelse

        <!-- Paginacja -->
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection