@extends('layouts.admin')

@section('title', 'Edytuj piwo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edytuj piwo: {{ $beer->name }}</h1>
    </div>

    <form action="{{ route('admin.beers.update', $beer) }}" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Punkt sprzedaży</label>
                <select name="beer_spot_id" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="">Wybierz punkt sprzedaży</option>
                    @foreach($beerSpots as $id => $name)
                        <option value="{{ $id }}" {{ (old('beer_spot_id', $beer->beer_spot_id) == $id) ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('beer_spot_id')
                    <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nazwa piwa</label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $beer->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('name')
                    <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Cena</label>
                <input type="number" 
                       name="price" 
                       value="{{ old('price', $beer->price) }}"
                       step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('price')
                    <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Typ piwa</label>
                <select name="type" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="">Wybierz typ piwa</option>
                    <option value="lager" {{ old('type', $beer->type) == 'lager' ? 'selected' : '' }}>Lager</option>
                    <option value="pilsner" {{ old('type', $beer->type) == 'pilsner' ? 'selected' : '' }}>Pilsner</option>
                    <option value="ale" {{ old('type', $beer->type) == 'ale' ? 'selected' : '' }}>Ale</option>
                    <option value="ipa" {{ old('type', $beer->type) == 'ipa' ? 'selected' : '' }}>IPA</option>
                    <option value="stout" {{ old('type', $beer->type) == 'stout' ? 'selected' : '' }}>Stout</option>
                    <option value="porter" {{ old('type', $beer->type) == 'porter' ? 'selected' : '' }}>Porter</option>
                    <option value="wheat" {{ old('type', $beer->type) == 'wheat' ? 'selected' : '' }}>Pszeniczne</option>
                    <option value="other" {{ old('type', $beer->type) == 'other' ? 'selected' : '' }}>Inne</option>
                </select>
                @error('type')
                    <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Zawartość alkoholu (%)</label>
                <input type="number" 
                       name="alcohol_percentage" 
                       value="{{ old('alcohol_percentage', $beer->alcohol_percentage) }}"
                       step="0.1"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent">
                @error('alcohol_percentage')
                    <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Opis</label>
                <textarea name="description" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                          >{{ old('description', $beer->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="available" {{ old('status', $beer->status) == 'available' ? 'selected' : '' }}>
                        Dostępne
                    </option>
                    <option value="unavailable" {{ old('status', $beer->status) == 'unavailable' ? 'selected' : '' }}>
                        Niedostępne
                    </option>
                </select>
                @error('status')
                    <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <a href="{{ route('admin.beers.index') }}" 
               class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                Anuluj
            </a>
            <button type="submit" 
                    class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-150">
                Zapisz zmiany
            </button>
        </div>
    </form>
</div>
@endsection