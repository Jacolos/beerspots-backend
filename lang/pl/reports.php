<?php

return [
    'title' => [
        'index' => 'Zarządzanie zgłoszeniami',
        'show' => 'Szczegóły zgłoszenia',
    ],

    'status' => [
        'label' => 'Status',
        'all' => 'Wszystkie statusy',
    ],

    'statuses' => [
        'pending' => 'Oczekujące',
        'resolved' => 'Rozwiązane',
        'rejected' => 'Odrzucone',
    ],

    'reason' => [
        'label' => 'Powód',
        'all' => 'Wszystkie powody',
    ],

    'reasons' => [
        'incorrect_price' => 'Niewłaściwa cena',
    	'incorrect_info' => 'Niepoprawne informacje', 
        'closed' => 'Miejsce już nie istnieje',
        'inappropriate' => 'Nieodpowiednie treści',
        'spam' => 'Spam/Reklama',
        'outdated' => 'Nieaktualne informacje',
        'wrong_location' => 'Błędna lokalizacja',
        'duplicate' => 'Duplikat',
        'other' => 'Inny powód',

    ],

    'messages' => [
        'status_updated' => 'Status zgłoszenia został zaktualizowany.',
        'update_failed' => 'Wystąpił błąd podczas aktualizacji zgłoszenia.',
        'deleted' => 'Zgłoszenie zostało usunięte.',
        'delete_failed' => 'Wystąpił błąd podczas usuwania zgłoszenia.',
        'cannot_delete_pending' => 'Nie można usunąć oczekującego zgłoszenia.',
        'bulk_updated' => 'Wybrane zgłoszenia zostały zaktualizowane.',
        'bulk_update_failed' => 'Wystąpił błąd podczas masowej aktualizacji zgłoszeń.',
    ],

    'actions' => [
        'view' => 'Zobacz',
        'edit' => 'Edytuj',
        'delete' => 'Usuń',
        'resolve' => 'Rozwiąż',
        'reject' => 'Odrzuć',
        'mark_resolved' => 'Oznacz jako rozwiązane',
        'mark_rejected' => 'Oznacz jako odrzucone',
        'back_to_list' => 'Powrót do listy',
        'confirm_delete' => 'Czy na pewno chcesz usunąć to zgłoszenie?',
        'confirm_resolve' => 'Czy na pewno chcesz rozwiązać to zgłoszenie?',
        'confirm_reject' => 'Czy na pewno chcesz odrzucić to zgłoszenie?',
    ],

    'filters' => [
        'search' => 'Szukaj w zgłoszeniach...',
        'date' => [
            'all' => 'Cały okres',
            'today' => 'Dzisiaj',
            'week' => 'Ostatni tydzień',
            'month' => 'Ostatni miesiąc',
        ],
        'sort' => [
            'latest' => 'Najnowsze',
            'oldest' => 'Najstarsze',
        ],
        'reset' => 'Resetuj filtry',
        'apply' => 'Zastosuj filtry',
    ],

    'stats' => [
        'total' => 'Wszystkie zgłoszenia',
        'pending' => 'Oczekujące',
        'resolved' => 'Rozwiązane',
        'rejected' => 'Odrzucone',
        'this_month' => 'W tym miesiącu',
        'change' => 'Zmiana',
        'vs_last_month' => 'vs. poprzedni miesiąc',
    ],

    'fields' => [
        'id' => 'ID',
        'date' => 'Data',
        'user' => 'Zgłaszający',
        'beer_spot' => 'Miejsce',
        'description' => 'Opis',
        'admin_notes' => 'Notatki administratora',
        'created_at' => 'Data utworzenia',
        'updated_at' => 'Data aktualizacji',
        'moderated_at' => 'Data moderacji',
        'moderated_by' => 'Moderowane przez',
        'status' => 'Status',
        'reason' => 'Powód',
    ],

    'details' => [
        'reporter' => 'Informacje o zgłaszającym',
        'report' => 'Szczegóły zgłoszenia',
        'moderation' => 'Informacje o moderacji',
        'related' => 'Powiązane zgłoszenia',
        'beer_spot' => 'Informacje o miejscu',
    ],

    'empty' => [
        'title' => 'Brak zgłoszeń',
        'description' => 'Nie znaleziono żadnych zgłoszeń spełniających kryteria',
    ],

    'validation' => [
        'reason_required' => 'Powód zgłoszenia jest wymagany.',
        'description_required' => 'Opis zgłoszenia jest wymagany.',
        'description_min' => 'Opis zgłoszenia musi zawierać co najmniej :min znaków.',
        'status_invalid' => 'Wybrany status jest nieprawidłowy.',
        'admin_notes_max' => 'Notatki administratora nie mogą być dłuższe niż :max znaków.',
    ],

    'notifications' => [
        'report_resolved' => [
            'title' => 'Zgłoszenie rozwiązane',
            'body' => 'Twoje zgłoszenie dotyczące :spot zostało rozpatrzone.',
        ],
        'report_rejected' => [
            'title' => 'Zgłoszenie odrzucone',
            'body' => 'Twoje zgłoszenie dotyczące :spot zostało odrzucone.',
        ],
    ],
];