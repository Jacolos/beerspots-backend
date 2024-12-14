@extends('layouts.admin')

@section('title', 'Piwa')

@section('content')

    <div class="mb-4 flex gap-4">
        <form class="flex-1">
            <input type="text" 
                   name="search" 
                   placeholder="Szukaj..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent">
        </form>
        <select name="status" 
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent" 
                onchange="this.form.submit()">
            <option value="">Wszystkie statusy</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Dostępne</option>
            <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Niedostępne</option>
        </select>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nazwa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Punkt sprzedaży</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cena</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Typ</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Akcje</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($beers as $beer)
                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $beer->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $beer->beerSpot->name }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap text-gray-900 dark:text-white">{{ number_format($beer->price, 2) }} zł</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-gray-900 dark:text-white">{{ $beer->type }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-sm font-medium inline-flex items-center justify-center
                                {{ $beer->status === 'available' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                {{ $beer->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('admin.beers.edit', $beer) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-150">
                                    Edytuj
                                </a>
                                <form action="{{ route('admin.beers.destroy', $beer) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Czy na pewno chcesz usunąć to piwo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors duration-150">
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

    <div class="mt-4 dark:text-white">
        {{ $beers->links() }}
    </div>
@endsection