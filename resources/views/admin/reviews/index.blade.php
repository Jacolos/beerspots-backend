@extends('layouts.admin')

@section('title', 'Zarządzanie opiniami')

@section('content')
    <div class="space-y-6">
        <!-- Header with stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($stats as $stat)
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                @if(isset($stat['change']))
                <p class="mt-1 text-sm {{ $stat['change'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $stat['change'] >= 0 ? '+' : '' }}{{ $stat['change'] }}% vs. poprzedni miesiąc
                </p>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Szukaj w opiniach..."
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
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Oczekujące</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Zatwierdzone</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Odrzucone</option>
                    </select>

                    <select name="rating" 
                            onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <option value="">Wszystkie oceny</option>
                        @foreach(range(1, 5) as $rating)
                            <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                                {{ $rating }} ⭐
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Bulk Actions -->
                <div class="flex items-center gap-2">
                    <select id="bulkAction" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                        <option value="">Akcje grupowe</option>
                        <option value="approve">Zatwierdź zaznaczone</option>
                        <option value="reject">Odrzuć zaznaczone</option>
                        <option value="delete">Usuń zaznaczone</option>
                    </select>
                    <button type="button" 
                            onclick="handleBulkAction()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Wykonaj
                    </button>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
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
                                Użytkownik/Miejsce
                            </th>
			    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
				Treść opinii
			    </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Ocena
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Data
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Akcje
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="w-4 p-4">
                                    <input type="checkbox" 
                                           value="{{ $review->id }}"
                                           class="review-checkbox rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $review->user->name }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $review->beerSpot->name }}
                                        </span>
                                    </div>
                                </td>
				<td class="px-6 py-4">
				    <p class="text-sm text-gray-500 dark:text-gray-400">
				        {{ $review->comment }}
				    </p>
				</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="text-lg font-medium {{ $review->rating >= 4 ? 'text-green-600 dark:text-green-400' : ($review->rating <= 2 ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400') }}">
                                            {{ $review->rating }}
                                        </span>
                                        <span class="ml-1 text-yellow-400">★</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $review->status === 'approved' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                        {{ $review->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : '' }}
                                        {{ $review->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                                        {{ $review->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $review->created_at->format('d.m.Y H:i') }}
                                </td>
<td class="px-6 py-4 whitespace-nowrap text-right min-w-[280px]">
    <div class="flex flex-col gap-2">
        @if($review->status === 'pending')
            <div class="flex justify-end gap-2">
                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" 
                            class="bg-green-500 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-150">
                        Zatwierdź
                    </button>
                </form>
                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" 
                            class="bg-yellow-500 dark:bg-yellow-600 hover:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-150">
                        Odrzuć
                    </button>
                </form>
            </div>
        @endif
        
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.reviews.show', $review) }}" 
                class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-150">
                Szczegóły
            </a>

            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline-flex">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Czy na pewno chcesz usunąć tę opinię?')"
                        class="bg-red-500 dark:bg-red-600 hover:bg-red-600 dark:hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors duration-150">
                    Usuń
                </button>
            </form>
        </div>
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
        </form>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $reviews->appends(request()->query())->links() }}
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle select all checkbox
        document.getElementById('selectAll')?.addEventListener('change', function() {
            const checkboxes = document.getElementsByClassName('review-checkbox');
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

            const checkboxes = document.getElementsByClassName('review-checkbox');
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Wybierz przynajmniej jedną opinię');
                return;
            }

            if (confirm('Czy na pewno chcesz wykonać tę akcję dla wybranych opinii?')) {
                const form = document.getElementById('bulkActionForm');
                form.action = `/admin/reviews/bulk/${action}`;
                
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
    </script>
    @endpush
@endsection