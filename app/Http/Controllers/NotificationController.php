<?php

namespace App\Http\Controllers;

use App\Models\NotificationModel;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = NotificationModel::getNotificationsByLoggedUser();

        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function get()
    {
        $notifications = NotificationModel::getNotificationsByLoggedUser(true);

        return response()->json($notifications);
    }

    public function read()
    {
        NotificationModel::where('to_user', session('user')['id'])->update([
            'viewed' => 1
        ]);

        return response()->json(['ok' => true, 'message' => 'Notificações atualizadas com sucesso!']);
    }
}
