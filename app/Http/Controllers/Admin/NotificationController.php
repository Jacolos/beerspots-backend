<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->notifications();

        // Filtrowanie po statusie
        if ($request->status) {
            if ($request->status === 'unread') {
                $query = auth()->user()->unreadNotifications();
            } elseif ($request->status === 'read') {
                $query = auth()->user()->readNotifications();
            }
        }

        // Filtrowanie po typie
        if ($request->type) {
            $query->where('type', 'LIKE', '%' . $request->type . '%');
        }

        // Filtrowanie po dacie
        if ($request->date) {
            if ($request->date === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($request->date === 'week') {
                $query->whereDate('created_at', '>=', now()->subWeek());
            } elseif ($request->date === 'month') {
                $query->whereDate('created_at', '>=', now()->subMonth());
            }
        }

        $notifications = $query->latest()->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return back()->with('success', 'Powiadomienie oznaczono jako przeczytane');
    }

    public function markAsUnread(DatabaseNotification $notification)
    {
        $notification->markAsUnread();
        return back()->with('success', 'Powiadomienie oznaczono jako nieprzeczytane');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Wszystkie powiadomienia oznaczono jako przeczytane');
    }

    public function destroy(DatabaseNotification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Powiadomienie zostało usunięte');
    }

    public function destroyAll()
    {
        auth()->user()->notifications()->delete();
        return back()->with('success', 'Wszystkie powiadomienia zostały usunięte');
    }
}