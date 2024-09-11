<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Admin;

class AdminNotification extends Controller
{
    public function allnotification()
    {
        $admin = auth()->user();
        return response()->json([
            "notification" => $admin->notifications
        ]);
    }

    public function unread()
    {
        $admin = auth()->user();
        return response()->json([
            "notification" => $admin->unreadNotifications
        ]);
    }

    public function readall()
    {
        $admin = auth()->user();
        foreach($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
    }

    public function readone($id)
    {
        $not = DB::table('notifications')->where('id', $id)->update(['read_at' => now()]);
        return response()->json(["message" => "successfuly"]);
    }

    public function deleteall()
    {
        $admin = Admin::find(auth()->id());
        $admin->notifications()->delete();
        return response()->json(["message" => "deleted"]);
    }

    public function deleteone($id)
    {
        DB::table('notifications')->where('id',$id)->delete();
        return response()->json(["message" => "deleted"]);
    }
}
