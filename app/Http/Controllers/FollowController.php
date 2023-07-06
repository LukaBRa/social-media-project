<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(Request $request){
        $user = User::firstWhere('id', $request->signedUserId);
        $friend = User::firstWhere('id', $request->friendIdReq);
        $user->followed()->attach($friend->id);

        $notification = new Notification;
        $notification->user_id = $request->friendIdReq;
        $notification->sender_id = $request->signedUserId;
        $notification->post_id = "nema";
        $notification->notification = "Vas je zapratio/la";
        $notification->status = "unread";
        $notification->save();

        event(new NewNotification($notification));

        return response()->json(['message' => 'Success']);
    }

    public function unfollow(Request $request){
        $user = User::firstWhere('id', $request->signedUserId);
        $friend = User::firstWhere('id', $request->friendIdReq);
        $user->followed()->detach($friend->id);

        return response()->json(['message' => 'Success']);
    }

    public function isFollowing(Request $request){
        $user = User::firstWhere('id', $request->singedUserId);
        $friend = User::firstWhere('id', $request->friendId);
        $isFollowing = $user->followed()->where('users.id', $friend->id)->exists();

        if($isFollowing){
            return response()->json(['message' => 'true']);
        }

        return response()->json(['message' => 'false']);
    }

    public function isBothFollowing(Request $request){
        $user = User::firstWhere('id', $request->singedUserId);
        $friend = User::firstWhere('id', $request->friendId);
        if($user->followed()->where('users.id', $friend->id)->exists() && $friend->followed()->where('users.id', $user->id)->exists()){
            return response()->json(['message' => 'true']);
        }
        return response()->json(['message' => 'false']);
    }

}
