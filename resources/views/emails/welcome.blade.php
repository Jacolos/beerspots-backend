@component('mail::message')
<div style="text-align: center; margin-bottom: 30px;">
    <img src="{{ asset('images/logo.png') }}" alt="BeerSpots Logo" style="max-width: 200px;">
</div>

# 🍺 Witaj w świecie BeerSpots, {{ $user->name }}!

Cieszymy się, że dołączyłeś do społeczności miłośników dobrego piwa. Twoja przygoda z BeerSpots właśnie się zaczyna!

<div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h2 style="margin-top: 0;">Co możesz robić w BeerSpots?</h2>
    <ul style="list-style-type: none; padding-left: 0;">
        <li style="margin: 10px 0;">📍 Odkrywać najlepsze punkty z piwem</li>
        <li style="margin: 10px 0;">⭐ Dzielić się opiniami</li>
        <li style="margin: 10px 0;">🔍 Sprawdzać ceny i dostępność</li>
        <li style="margin: 10px 0;">📱 Być na bieżąco z nowościami</li>
    </ul>
</div>

@component('mail::button', ['url' => config('app.frontend_url')])
Rozpocznij eksplorację
@endcomponent

Masz pytania? Odpowiedz na tego maila, a nasz zespół chętnie pomoże!

Na zdrowie! 🍻<br>
Zespół BeerSpots

<div style="text-align: center; margin-top: 30px; color: #6b7280; font-size: 12px;">
    © {{ date('Y') }} BeerSpots. Wszystkie prawa zastrzeżone.
</div>
@endcomponent