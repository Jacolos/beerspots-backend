@extends('layouts.admin')

@section('title', 'Edytuj piwo')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.beers.index') }}" 
               class="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Powrót do listy
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                Edytuj piwo: {{ $beer->name }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Lokalizacja: {{ $beer->beerSpot->name }}
            </p>
        </div>
    </div>

    <!-- Edit form -->
    <form action="{{ route('admin.beers.update', $beer) }}" 
          method="POST" 
          class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Podstawowe informacje</h2>
            
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nazwa piwa <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name"
                           name="name" 
                           value="{{ old('name', $beer->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Cena (PLN) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="price"
                                   name="price" 
                                   value="{{ old('price', $beer->price) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">PLN</span>
                            </div>
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alcohol Percentage -->
                    <div>
                        <label for="alcohol_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Zawartość alkoholu (%)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="alcohol_percentage"
                                   name="alcohol_percentage" 
                                   value="{{ old('alcohol_percentage', $beer->alcohol_percentage) }}"
                                   step="0.1"
                                   min="0"
                                   max="100"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">%</span>
                            </div>
                        </div>
                        @error('alcohol_percentage')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Typ piwa <span class="text-red-500">*</span>
                    </label>
                    <select name="type" 
                            id="type"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500"
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
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Opis
                    </label>
                    <textarea name="description" 
                              id="description"
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">{{ old('description', $beer->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h2>
            
            <div class="flex flex-col space-y-4">
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" 
                               name="status" 
                               value="available"
                               class="text-blue-600 dark:text-blue-500 border-gray-300 dark:border-gray-600 focus:ring-blue-500"
                               {{ old('status', $beer->status) == 'available' ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Dostępne</span>
                    </label>

                    <label class="inline-flex items-center">
                        <input type="radio" 
                               name="status" 
                               value="unavailable"
                               class="text-blue-600 dark:text-blue-500 border-gray-300 dark:border-gray-600 focus:ring-blue-500"
                               {{ old('status', $beer->status) == 'unavailable' ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Niedostępne</span>
                    </label>
                </div>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Aktualny status: 
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $beer->status === 'available' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                        {{ $beer->status === 'unavailable' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' : '' }}">
                        {{ $beer->status === 'available' ? 'Dostępne' : 'Niedostępne' }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.beers.index') }}" 
               class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                Anuluj
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-150">
                Zapisz zmiany
            </button>
        </div>
    </form>
</div>
@endsection