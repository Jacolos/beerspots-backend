@extends('layouts.admin')

@section('title', 'Edytuj punkt sprzedaży')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Edytuj punkt sprzedaży: {{ $beerSpot->name }}
        </h1>
    </div>

    <form action="{{ route('admin.beer-spots.update', $beerSpot) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Podstawowe informacje --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Podstawowe informacje</h2>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nazwa</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $beerSpot->name) }}"
                               class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 @error('name') border-red-500 dark:border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" 
                                class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 @error('status') border-red-500 dark:border-red-500 @enderror">
                            <option value="active" {{ old('status', $beerSpot->status) == 'active' ? 'selected' : '' }}>Aktywny</option>
                            <option value="inactive" {{ old('status', $beerSpot->status) == 'inactive' ? 'selected' : '' }}>Nieaktywny</option>
                            <option value="pending" {{ old('status', $beerSpot->status) == 'pending' ? 'selected' : '' }}>Oczekujący</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Adres</label>
                    <input type="text" 
                           name="address" 
                           value="{{ old('address', $beerSpot->address) }}"
                           class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 @error('address') border-red-500 dark:border-red-500 @enderror"
                           required>
                    @error('address')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Szerokość geograficzna</label>
                        <input type="number" 
                               name="latitude" 
                               value="{{ old('latitude', $beerSpot->latitude) }}"
                               step="any"
                               class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 @error('latitude') border-red-500 dark:border-red-500 @enderror"
                               required>
                        @error('latitude')
                            <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Długość geograficzna</label>
                        <input type="number" 
                               name="longitude" 
                               value="{{ old('longitude', $beerSpot->longitude) }}"
                               step="any"
                               class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 @error('longitude') border-red-500 dark:border-red-500 @enderror"
                               required>
                        @error('longitude')
                            <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Opis</label>
                    <textarea name="description" 
                             rows="3"
                             class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 @error('description') border-red-500 dark:border-red-500 @enderror">{{ old('description', $beerSpot->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Godziny otwarcia --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Godziny otwarcia</h2>
            
            @php
                $days = [
                    'monday' => 'Poniedziałek',
                    'tuesday' => 'Wtorek',
                    'wednesday' => 'Środa',
                    'thursday' => 'Czwartek',
                    'friday' => 'Piątek',
                    'saturday' => 'Sobota',
                    'sunday' => 'Niedziela'
                ];
            @endphp

            <div class="space-y-4">
                @foreach($days as $day => $dayName)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <div class="font-medium text-gray-700 dark:text-gray-300">{{ $dayName }}</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400">Otwarcie</label>
                                <input type="time"
                                       name="opening_hours[{{ $day }}][open]"
                                       value="{{ old('opening_hours.'.$day.'.open', $beerSpot->opening_hours[$day]['open'] ?? '') }}"
                                       class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400">Zamknięcie</label>
                                <input type="time"
                                       name="opening_hours[{{ $day }}][close]"
                                       value="{{ old('opening_hours.'.$day.'.close', $beerSpot->opening_hours[$day]['close'] ?? '') }}"
                                       class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="opening_hours[{{ $day }}][closed]"
                                       value="1"
                                       {{ old('opening_hours.'.$day.'.closed', isset($beerSpot->opening_hours[$day]['closed']) && $beerSpot->opening_hours[$day]['closed']) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 dark:text-blue-500 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-500 dark:bg-gray-700 mr-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Zamknięte</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Opcje dodatkowe --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Dodatkowe opcje</h2>
            
            <div class="space-y-4">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="verified" 
                           value="1"
                           {{ old('verified', $beerSpot->verified) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 dark:text-blue-500 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-500 dark:bg-gray-700 mr-2">
                    <span class="text-gray-700 dark:text-gray-300">Zweryfikowany punkt sprzedaży</span>
                </label>
            </div>
        </div>

        {{-- Przyciski akcji --}}
        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.beer-spots.index') }}" 
               class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                Anuluj
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-500 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg transition-colors duration-150">
                Zapisz zmiany
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obsługa pól czasu przy zaznaczeniu "Zamknięte"
    const dayContainers = document.querySelectorAll('[name^="opening_hours"]');
    dayContainers.forEach(container => {
        const day = container.name.match(/\[(.*?)\]/)[1];
        const closedCheckbox = document.querySelector(`[name="opening_hours[${day}][closed]"]`);
        const timeInputs = [
            document.querySelector(`[name="opening_hours[${day}][open]"]`),
            document.querySelector(`[name="opening_hours[${day}][close]"]`)
        ];

        if (closedCheckbox) {
            closedCheckbox.addEventListener('change', function() {
                timeInputs.forEach(input => {
                    input.disabled = this.checked;
                    if (this.checked) {
                        input.value = '';
                    }
                });
            });

            // Inicjalizacja stanu przy załadowaniu strony
            if (closedCheckbox.checked) {
                timeInputs.forEach(input => {
                    input.disabled = true;
                    input.value = '';
                });
            }
        }
    });
});
</script>
@endpush
@endsection