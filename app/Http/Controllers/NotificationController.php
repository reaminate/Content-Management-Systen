<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //all notifications for the logged in user
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->unreadNotifications()->get();
        foreach ($notifications as $notification){
            $notification->markAsRead();
        }
        return $notifications;
    }
}
