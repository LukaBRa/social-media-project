<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    public function store(Request $request){
        $comment = new Comment;
        $comment->user_id = $request->userId;
        $comment->post_id = $request->postId;
        $comment->comment_text = $request->commentText;
        $comment->save();
        
        $post = Post::firstWhere('id', $request->postId);
        $postUser = User::firstWhere('id', $post->user_id);

        $notification = new Notification;
        $notification->user_id = $postUser->id;
        $notification->sender_id = $request->userId;
        $notification->post_id = $request->postId;
        $notification->notification = "je komentarisao/la Vasu objavu";
        $notification->status = "unread";
        $notification->save();

        event(new NewNotification($notification));

        return response()->json(['newComment' => $comment]);
    }

    public function delete(Request $request){
        Comment::where('id', $request->commentID)->delete();

        return response()->json(['message' => 'Komentar je uspesno obrisan.']);
    }

    public function report(Request $request){
        $comment = Comment::firstWhere('id', $request->commentID);
        $comment->is_reported = true;
        $comment->save();

        return response()->json(['message' => 'Uspesno je prijavljen komentar.']);
    }

    public function removeFromReported(Request $request){
        $comment = Comment::firstWhere('id', $request->commentId);
        $comment->is_reported = false;
        $comment->save();

        return response()->json(['message' => 'Komentar je uspesno uklonjen sa liste prijavljenih.']);
    }

}
