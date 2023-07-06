<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function likePostController(String $userId, String $postId){

        $user = User::firstWhere('id', $userId);
        $user->likePost($postId);
        $post = Post::firstWhere('id', $postId);
        $postUser = User::firstWhere('id', $post->user_id);
        $newNotification = new Notification;
        $newNotification->user_id = $postUser->id;
        $newNotification->sender_id =$userId;
        $newNotification->post_id = $postId;
        $newNotification->notification = "je oznacio/la da mu se svidja Vasa objava";
        $newNotification->status = "unread";
        $newNotification->save();
        event(new NewNotification($newNotification));
        return response()->json(['message' => 'success']);
    }

    public function dislikePostController(String $userId, String $postId){
        $user = User::firstWhere('id', $userId);
        $user->dislikePost($postId);
        return response()->json(['message' => 'success']);
    }
}
