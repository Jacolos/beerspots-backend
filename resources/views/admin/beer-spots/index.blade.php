@extends('layouts.admin')

@section('title', 'Punkty sprzedaży')

@section('content')
    <div class="space-y-6">
        <!-- Header with stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Wszystkie punkty</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Aktywne</h3>
                <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['active'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Oczekujące</h3>
                <p class="mt-2 text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nieaktywne</h3>
                <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['inactive'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <form action="{{ route('admin.beer-spots.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Szukaj punktów..."
                               class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <span class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>

                    <select name="status" 
                            onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <option value="">Wszystkie statusy</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktywne</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Oczekujące</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nieaktywne</option>
                    </select>

                    <select name="verified" 
                            onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <option value="">Wszystkie</option>
                        <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Zweryfikowane</option>
                        <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Niezweryfikowane</option>
                    </select>
                </form>

                <!-- Bulk Actions -->
                <div class="flex items-center gap-2">
                    <select id="bulkAction" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <option value="">Akcje grupowe</option>
                        <option value="verify">Zweryfikuj zaznaczone</option>
	    		<option value="unverify">Usuń weryfikację</option>
                        <option value="activate">Aktywuj zaznaczone</option>
                        <option value="deactivate">Dezaktywuj zaznaczone</option>
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

        <!-- Beer Spots List -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
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
                                Nazwa/Adres
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Weryfikacja
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Liczba piw
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Średnia ocena
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Akcje
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($beerSpots as $spot)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <td class="w-4 p-4">
                                    <input type="checkbox" 
                                           value="{{ $spot->id }}"
                                           class="spot-checkbox rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $spot->name }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $spot->address }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $spot->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                        {{ $spot->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                        {{ $spot->status === 'inactive' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                                        {{ $spot->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($spot->verified)
                                        <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="ml-1 text-sm">Zweryfikowany</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-red-600 dark:text-red-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="ml-1 text-sm">Niezweryfikowany</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    {{ $spot->beers_count ?? 0 }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="text-lg font-medium {{ $spot->average_rating >= 4 ? 'text-green-600 dark:text-green-400' : ($spot->average_rating <= 2 ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400') }}">
                                            {{ number_format($spot->average_rating ?? 0, 1) }}
                                        </span>
                                        <span class="ml-1 text-yellow-400">★</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.beer-spots.show', $spot) }}" 
                                           class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-150">
                                            Szczegóły
                                        </a>
                                        @if(!$spot->verified)
                                            <form action="{{ route('admin.beer-spots.verify', $spot) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-green-500 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-150">
                                                    Zweryfikuj
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.beer-spots.destroy', $spot) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Czy na pewno chcesz usunąć ten punkt sprzedaży?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 dark:bg-red-600 hover:bg-red-600 dark:hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-150">
                                                Usuń
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hidden form for bulk actions -->
        <form id="bulkActionForm" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="_method" value="POST">
        </form>
<!-- Pagination -->
        <div class="mt-4">
            {{ $beerSpots->appends(request()->query())->links() }}
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle select all checkbox
        document.getElementById('selectAll')?.addEventListener('change', function() {
            const checkboxes = document.getElementsByClassName('spot-checkbox');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });

        // Handle bulk actions
        function handleBulkAction() {
            const action = document.getElementById('bulkAction').value;
            if (!action) {
                alert('Wybierz akcję');
                return;
            }

            const checkboxes = document.getElementsByClassName('spot-checkbox');
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Wybierz przynajmniej jeden punkt sprzedaży');
                return;
            }

            // Custom confirmation messages based on action
            const confirmationMessages = {
                verify: 'Czy na pewno chcesz zweryfikować wybrane punkty sprzedaży?',
    		unverify: 'Czy na pewno chcesz usunąć weryfikację wybranych punktów sprzedaży?',
                activate: 'Czy na pewno chcesz aktywować wybrane punkty sprzedaży?',
                deactivate: 'Czy na pewno chcesz dezaktywować wybrane punkty sprzedaży?',
                delete: 'Czy na pewno chcesz usunąć wybrane punkty sprzedaży? Ta operacja jest nieodwracalna.'
            };

            if (confirm(confirmationMessages[action] || 'Czy na pewno chcesz wykonać tę akcję?')) {
                const form = document.getElementById('bulkActionForm');
                
                // Set appropriate method and action based on the selected action
if (action === 'delete') {
    form.querySelector('input[name="_method"]').value = 'DELETE';
    form.action = '{{ route("admin.beer-spots.bulk-destroy") }}';
} else if (action === 'verify') {
    form.querySelector('input[name="_method"]').value = 'POST';
    form.action = '{{ route("admin.beer-spots.bulk-verify") }}';
} else if (action === 'unverify') {
    form.querySelector('input[name="_method"]').value = 'POST';
    form.action = '{{ route("admin.beer-spots.bulk-unverify") }}';
} else if (action === 'activate' || action === 'deactivate') {
    form.querySelector('input[name="_method"]').value = 'PUT';
    form.action = '/admin/beer-spots/bulk-status/' + action;
}                
                // Clear any existing hidden inputs
                const existingInputs = form.querySelectorAll('input[name="ids[]"]');
                existingInputs.forEach(input => input.remove());
                
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

        // Add individual checkbox change handler to update "Select All" state
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const individualCheckboxes = document.getElementsByClassName('spot-checkbox');

            Array.from(individualCheckboxes).forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(individualCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(individualCheckboxes).some(cb => cb.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                });
            });
        });
    </script>
    @endpush
@endsection