<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NotificationModel extends Model
{
    protected $table = "notifications";
    protected $primaryKey = "id";
    protected $guarded = [];

    public static function getNotificationsByLoggedUser($notViewedOnly = false)
    {
        $query = DB::table('notifications as n')
            ->select(
                '*',
                DB::raw("to_char(n.created_at, 'dd/mm/yyyy hh24:mi') as created_at"))
            ->where('n.to_user', session('user')['id']);

        if ($notViewedOnly) {
            $query->where('n.viewed', '=', 0);
        }

        $notifications = $query->get();

        return $notifications;
    }
}
