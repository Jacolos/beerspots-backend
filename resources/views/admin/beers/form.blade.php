@extends('layouts.admin')

@section('title', isset($beer) ? 'Edytuj piwo' : 'Dodaj piwo')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
            {{ isset($beer) ? 'Edytuj piwo' : 'Dodaj piwo' }}
        </h1>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ isset($beer) ? route('admin.beers.update', $beer) : route('admin.beers.store') }}"
                  method="POST"
                  class="space-y-4">
                @csrf
                @if(isset($beer))
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Punkt sprzedaży</label>
                    <select name="beer_spot_id" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent" 
                            required>
                        <option value="">Wybierz punkt sprzedaży</option>
                        @foreach($beerSpots as $id => $name)
                            <option value="{{ $id }}"
                                {{ (isset($beer) && $beer->beer_spot_id == $id) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nazwa</label>
                    <input type="text" 
                           name="name" 
                           value="{{ $beer->name ?? old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Cena</label>
                    <input type="number" 
                           name="price" 
                           value="{{ $beer->price ?? old('price') }}"
                           step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Typ</label>
                    <input type="text" 
                           name="type" 
                           value="{{ $beer->type ?? old('type') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Zawartość alkoholu (%)</label>
                    <input type="number" 
                           name="alcohol_percentage" 
                           value="{{ $beer->alcohol_percentage ?? old('alcohol_percentage') }}"
                           step="0.1"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent" 
                            required>
                        <option value="available" 
                                {{ (isset($beer) && $beer->status == 'available') ? 'selected' : '' }}>
                            Dostępne
                        </option>
                        <option value="unavailable"
                                {{ (isset($beer) && $beer->status == 'unavailable') ? 'selected' : '' }}>
                            Niedostępne
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Opis</label>
                    <textarea name="description" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent">{{ $beer->description ?? old('description') }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" 
                            class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-150">
                        {{ isset($beer) ? 'Zapisz zmiany' : 'Dodaj piwo' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection