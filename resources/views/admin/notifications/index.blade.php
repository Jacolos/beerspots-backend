@extends('layouts.admin')

@section('title', 'Powiadomienia')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Powiadomienia</h1>
            @if($unreadCount = auth()->user()->unreadNotifications()->count())
                <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                    {{ $unreadCount }} nieprzeczytanych
                </span>
            @endif
        </div>
        
        <!-- Quick Actions -->
        <div class="flex items-center space-x-3">
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/50 hover:bg-blue-100 dark:hover:bg-blue-900/70 transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Oznacz wszystkie jako przeczytane
                </button>
            </form>
            <form action="{{ route('admin.notifications.destroy-all') }}" 
                  method="POST" 
                  onsubmit="return confirm('Czy na pewno chcesz usunąć wszystkie powiadomienia?')" 
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/50 hover:bg-red-100 dark:hover:bg-red-900/70 transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Usuń wszystkie
                </button>
            </form>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="status" 
                    class="rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                    onchange="this.form.submit()">
                <option value="">Wszystkie statusy</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Nieprzeczytane</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Przeczytane</option>
            </select>

            <select name="type" 
                    class="rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                    onchange="this.form.submit()">
                <option value="">Wszystkie typy</option>
                <option value="NewBeerSpot" {{ request('type') == 'NewBeerSpot' ? 'selected' : '' }}>Nowy punkt</option>
                <option value="NewReview" {{ request('type') == 'NewReview' ? 'selected' : '' }}>Nowa recenzja</option>
                <option value="NewReport" {{ request('type') == 'NewReport' ? 'selected' : '' }}>Nowe zgłoszenie</option>
            </select>

            <select name="date" 
                    class="rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                    onchange="this.form.submit()">
                <option value="">Cały okres</option>
                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Dzisiaj</option>
                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Ostatni tydzień</option>
                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Ostatni miesiąc</option>
            </select>
        </form>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition duration-150 hover:border-blue-500 dark:hover:border-blue-400 {{ !$notification->read_at ? 'border-l-4 border-l-blue-500 dark:border-l-blue-400' : '' }}">
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <!-- Icon based on notification type -->
                                <div @class([
                                    'flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center',
                                    'bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400' => Str::contains($notification->type, 'BeerSpot'),
                                    'bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400' => Str::contains($notification->type, 'Review'),
                                    'bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-400' => Str::contains($notification->type, 'Report'),
                                ])>
                                    @if(Str::contains($notification->type, 'BeerSpot'))
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                    @elseif(Str::contains($notification->type, 'Review'))
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ Str::title(str_replace('_', ' ', Str::afterLast($notification->type, '\\'))) }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $notification->data['message'] ?? 'Brak treści' }}
                            </p>
                            
                            @if(isset($notification->data['action_url']))
                                <a href="{{ $notification->data['action_url'] }}" 
                                   class="mt-2 inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    Zobacz szczegóły
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex items-start space-x-2 ml-4">
                            @if($notification->read_at)
                                <form action="{{ route('admin.notifications.mark-unread', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" 
                                            title="Oznacz jako nieprzeczytane">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.notifications.mark-read', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="p-2 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/50 transition-colors" 
                                            title="Oznacz jako przeczytane">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
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
                                        class="p-2 text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors" 
                                        title="Usuń">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Brak powiadomień</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Nie masz żadnych powiadomień do wyświetlenia.
                    </p>
                </div>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Automatyczne odświeżanie co 30 sekund
    setInterval(function() {
        window.livewire && window.livewire.emit('refresh');
    }, 30000);
</script>
@endpush
@endsection