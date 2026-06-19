<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()->paginate(15);
        
        $stats = [
            'total'  => $user->notifications()->count(),
            'unread' => $user->unreadNotifications()->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirect to the target URL if it exists
        if (isset($notification->data['url']) && $notification->data['url'] !== '#') {
            return redirect($notification->data['url']);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification removed.');
    }
}