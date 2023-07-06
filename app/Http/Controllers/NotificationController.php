<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function update(Request $request){

        if($request->userId){
            Notification::where('user_id', $request->userId)->update(['status' => 'read']);
        }

        Notification::where('id', $request->notificationId)->update(['status' => 'read']);
        
    }
}
