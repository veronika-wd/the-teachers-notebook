<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::query()->orderByDesc('created_at')->get();
        return view('notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function store(Request $request)
    {
        Notification::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('notifications.index');
    }

    public function show(Notification $notification)
    {
        $comments = Comment::query()->where('notification_id', $notification->id)->orderByDesc('created_at')->get();

        return view('notification', [
            'notification' => $notification,
            'comments' => $comments,
        ]);
    }

    public function comment(Request $request, Notification $notification)
    {
        Comment::create([
            'notification_id' => $notification->id,
            'user_id' => auth()->user()->id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('notifications.show', $notification);
    }
}
