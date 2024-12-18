@extends('layouts.admin')

@section('title', 'Zarządzanie piwami')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Dostępne</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $beers->where('status', 'available')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/50">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Oczekujące</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $beers->where('status', 'pending')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Niedostępne</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $beers->where('status', 'unavailable')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row gap-4 flex-1">
                <form class="flex-1" action="{{ route('admin.beers.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Szukaj piw..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <span class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>
                </form>

                <select name="status" 
                        onchange="this.form.submit()"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                    <option value="">Wszystkie statusy</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Dostępne</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Oczekujące</option>
                    <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Niedostępne</option>
                </select>

                <!-- Bulk Actions -->
                <div class="flex items-center gap-2">
                    <select id="bulkAction" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <option value="">Akcje grupowe</option>
                        <option value="makeAvailable">Oznacz jako dostępne</option>
                        <option value="makeUnavailable">Oznacz jako niedostępne</option>
                        <option value="delete">Usuń zaznaczone</option>
                    </select>
                    <button type="button" 
                            onclick="handleBulkAction()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150">
                        Wykonaj
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Beers Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="w-4 p-4">
                            <input type="checkbox" 
                                   id="selectAll"
                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nazwa piwa
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Punkt sprzedaży
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Cena
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Akcje
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($beers as $beer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="w-4 p-4">
                                <input type="checkbox" 
                                       value="{{ $beer->id }}"
                                       class="beer-checkbox rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $beer->name }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $beer->type }} • {{ $beer->alcohol_percentage }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.beer-spots.show', $beer->beerSpot) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    {{ $beer->beerSpot->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($beer->price, 2) }} zł
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $beer->status === 'available' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                        {{ $beer->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                        {{ $beer->status === 'unavailable' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                                        {{ ucfirst($beer->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center space-x-2">
                                    <!-- Status Update Buttons -->
                                    <form action="{{ route('admin.beers.update', $beer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="available">
                                        <button type="submit" 
                                                class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm transition-colors duration-150 {{ $beer->status === 'available' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $beer->status === 'available' ? 'disabled' : '' }}>
                                            Dostępne
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.beers.update', $beer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="unavailable">
                                        <button type="submit" 
                                                class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm transition-colors duration-150 {{ $beer->status === 'unavailable' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $beer->status === 'unavailable' ? 'disabled' : '' }}>
                                            Niedostępne
                                        </button>
                                    </form>

                                    <!-- Edit and Delete -->
                                    <div class="flex items-center space-x-2 ml-2">
                                        <a href="{{ route('admin.beers.edit', $beer) }}" 
                                           class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/50 transition-colors duration-150">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.beers.destroy', $beer) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Czy na pewno chcesz usunąć to piwo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors duration-150">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-lg font-medium">Brak piw do wyświetlenia</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Dodaj pierwsze piwo klikając przycisk "Dodaj piwo"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hidden form for bulk actions -->
    <form id="bulkActionForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="_method" value="PUT">
    </form>

    <!-- Pagination -->
    @if($beers->hasPages())
        <div class="mt-4">
            {{ $beers->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle select all checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        const beerCheckboxes = document.getElementsByClassName('beer-checkbox');

        selectAllCheckbox?.addEventListener('change', function() {
            Array.from(beerCheckboxes).forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Update "Select All" state when individual checkboxes change
        Array.from(beerCheckboxes).forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(beerCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(beerCheckboxes).some(cb => cb.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            });
        });
    });

    // Handle bulk actions
    function handleBulkAction() {
        const action = document.getElementById('bulkAction').value;
        if (!action) {
            alert('Wybierz akcję');
            return;
        }

        const checkboxes = document.getElementsByClassName('beer-checkbox');
        const selectedIds = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (selectedIds.length === 0) {
            alert('Wybierz przynajmniej jedno piwo');
            return;
        }

        const confirmationMessages = {
            makeAvailable: 'Czy na pewno chcesz oznaczyć wybrane piwa jako dostępne?',
            makeUnavailable: 'Czy na pewno chcesz oznaczyć wybrane piwa jako niedostępne?',
            delete: 'Czy na pewno chcesz usunąć wybrane piwa? Ta operacja jest nieodwracalna.'
        };

        if (confirm(confirmationMessages[action] || 'Czy na pewno chcesz wykonać tę akcję?')) {
            const form = document.getElementById('bulkActionForm');
            
            // Set appropriate method and action based on the selected action
            if (action === 'delete') {
                form.querySelector('input[name="_method"]').value = 'DELETE';
                form.action = '{{ route("admin.beers.bulk-destroy") }}';
            } else {
                form.querySelector('input[name="_method"]').value = 'PUT';
                form.action = '/admin/beers/bulk-status/' + action;
            }
            
            // Clear existing hidden inputs
            form.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());
            
            // Add selected IDs to form
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            form.submit();
        }
    }
</script>
@endpush
@endsection