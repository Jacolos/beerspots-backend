@extends('layouts.admin')

@section('title', isset($beerSpot) ? 'Edytuj punkt sprzedaży' : 'Dodaj punkt sprzedaży')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
        {{ isset($beerSpot) ? 'Edytuj punkt sprzedaży' : 'Dodaj punkt sprzedaży' }}
    </h1>

    <form action="{{ isset($beerSpot) ? route('admin.beer-spots.update', $beerSpot) : route('admin.beer-spots.store') }}"
          method="POST"
          class="space-y-6">
        @csrf
        @if(isset($beerSpot))
            @method('PUT')
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Podstawowe informacje</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nazwa</label>
                    <input type="text" 
                           name="name" 
                           value="{{ $beerSpot->name ?? old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent" 
                            required>
                        <option value="active" {{ (isset($beerSpot) && $beerSpot->status == 'active') ? 'selected' : '' }}>
                            Aktywny
                        </option>
                        <option value="inactive" {{ (isset($beerSpot) && $beerSpot->status == 'inactive') ? 'selected' : '' }}>
                            Nieaktywny
                        </option>
                        <option value="pending" {{ (isset($beerSpot) && $beerSpot->status == 'pending') ? 'selected' : '' }}>
                            Oczekujący
                        </option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Adres</label>
                <input type="text" 
                       name="address" 
                       value="{{ $beerSpot->address ?? old('address') }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Szerokość geograficzna</label>
                    <input type="number" 
                           name="latitude" 
                           value="{{ $beerSpot->latitude ?? old('latitude') }}"
                           step="any"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Długość geograficzna</label>
                    <input type="number" 
                           name="longitude" 
                           value="{{ $beerSpot->longitude ?? old('longitude') }}"
                           step="any"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Opis</label>
                <textarea name="description" 
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent">{{ $beerSpot->description ?? old('description') }}</textarea>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Godziny otwarcia</h2>
            
            @php
                $days = ['monday' => 'Poniedziałek', 'tuesday' => 'Wtorek', 'wednesday' => 'Środa', 
                        'thursday' => 'Czwartek', 'friday' => 'Piątek', 'saturday' => 'Sobota', 
                        'sunday' => 'Niedziela'];
                $openingHours = isset($beerSpot) ? $beerSpot->opening_hours : [];
            @endphp

            @foreach($days as $day => $dayName)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 items-center">
                    <div class="font-medium text-gray-700 dark:text-gray-300">{{ $dayName }}</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400">Otwarcie</label>
                            <input type="time"
                                   name="opening_hours[{{ $day }}][open]"
                                   value="{{ $openingHours[$day]['open'] ?? '' }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400">Zamknięcie</label>
                            <input type="time"
                                   name="opening_hours[{{ $day }}][close]"
                                   value="{{ $openingHours[$day]['close'] ?? '' }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="opening_hours[{{ $day }}][closed]"
                                   class="mr-2 rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 dark:bg-gray-700"
                                   {{ isset($openingHours[$day]['closed']) && $openingHours[$day]['closed'] ? 'checked' : '' }}>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Zamknięte</span>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Dodatkowe opcje</h2>
            
            <div class="space-y-4">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="verified" 
                           value="1"
                           {{ (isset($beerSpot) && $beerSpot->verified) ? 'checked' : '' }}
                           class="mr-2 rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 dark:bg-gray-700">
                    <span class="text-gray-700 dark:text-gray-300">Zweryfikowany punkt sprzedaży</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.beer-spots.index') }}" 
               class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                Anuluj
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-150">
                {{ isset($beerSpot) ? 'Zapisz zmiany' : 'Dodaj punkt sprzedaży' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obsługa checkboxów "Zamknięte"
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

            // Inicjalizacja stanu przy załadowaniu
            if (closedCheckbox.checked) {
                timeInputs.forEach(input => {
                    input.disabled = true;
                });
            }
        }
    });
});
</script>
@endpush
@endsection