@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Users Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Użytkownicy</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">+{{ $stats['users_this_month'] }} w tym miesiącu</p>
                </div>
            </div>
        </div>

        <!-- Beer Spots Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Punkty Sprzedaży</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_spots']) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $stats['verified_spots'] }} zweryfikowanych
                    </p>
                </div>
            </div>
        </div>

        <!-- Reports Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Zgłoszenia</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_reports']) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $stats['pending_reports'] }} oczekujących
                    </p>
                </div>
            </div>
        </div>

        <!-- Reviews Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Opinie</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_reviews']) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Średnia ocena: {{ number_format($stats['average_rating'], 1) }}/5
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pending Spots -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800/50 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200">Oczekujące punkty</h3>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending_spots'] }}</p>
                </div>
                <a href="{{ route('admin.beer-spots.index', ['status' => 'pending']) }}" 
                   class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300">
                    Zarządzaj →
                </a>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800/50 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-200">Oczekujące opinie</h3>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['pending_reviews'] }}</p>
                </div>
                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" 
                   class="text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300">
                    Zarządzaj →
                </a>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800/50 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200">Oczekujące zgłoszenia</h3>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['pending_reports'] }}</p>
                </div>
                <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" 
                   class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                    Zarządzaj →
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Latest Reports -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Najnowsze zgłoszenia</h2>
            <div class="space-y-4">
                @foreach($stats['latest_reports'] as $report)
                    <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $report->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                    {{ $report->status === 'resolved' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                    {{ $report->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $report->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ Str::limit($report->description, 100) }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Zgłoszony punkt: {{ $report->beerSpot->name }}
                            </p>
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('admin.reports.show', $report) }}" 
                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                Szczegóły
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Latest Reviews -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Ostatnie opinie</h2>
            <div class="space-y-4">
                @foreach($stats['latest_reviews'] as $review)
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">{{ $review->beerSpot->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    przez {{ $review->user->name }}
                                </p>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $review->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : '' }}
                                    {{ $review->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' : '' }}
                                    {{ $review->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : '' }}">
                                    {{ ucfirst($review->status) }}
                                </span>
                                <span class="ml-2 flex items-center text-yellow-400 dark:text-yellow-300">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium">{{ $review->rating }}</span>
                                </span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($review->comment, 100) }}</p>
                        <div class="mt-2 text-right">
                            <a href="{{ route('admin.reviews.show', $review) }}" 
                               class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                Szczegóły →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Latest Spots -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Najnowsze punkty sprzedaży</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nazwa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Adres</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dodano</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Akcje</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($stats['latest_spots'] as $spot)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $spot->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $spot->address }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $spot->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : '' }}
                                    {{ $spot->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' : '' }}
                                    {{ $spot->status === 'inactive' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : '' }}">
                                    {{ ucfirst($spot->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $spot->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('admin.beer-spots.show', $spot) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    Szczegóły →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Real-time update functionality could be added here if needed
</script>
@endpush