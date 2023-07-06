<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getLastMessage(Request $request){
        $latestMessage = Message::where('sender_id', $request->friendId)
                                ->where('receiver_id', $request->signedUserId)
                                ->latest()->first();
        return response()->json($latestMessage);
    }

    public function getUnreadCountSingle(Request $request){
        $unreadMessages = Message::where('sender_id', $request->friendId)
                                 ->where('receiver_id', $request->signedUserId)
                                 ->where('status', "unread")
                                 ->count();
        return response()->json($unreadMessages);
    }

    public function send(Request $request){
        $newMessage = new Message;
        $newMessage->sender_id = $request->senderId;
        $newMessage->receiver_id = $request->receiverId;
        $newMessage->message = $request->message;
        $newMessage->status = "unread";
        $newMessage->save();

        event(new NewMessage($newMessage));

        return response()->json($newMessage);
    }

    public function getMessages(Request $request){
        $messages = Message::whereIn('sender_id', [$request->friendId, $request->signedUserId])
                           ->whereIn('receiver_id', [$request->friendId, $request->signedUserId])
                           ->get();

        return response()->json($messages);
    }

    public function markRead(Request $request){
        Message::where('sender_id', $request->friendId)
               ->where('receiver_id', $request->signedUserId)
               ->update(['status' => 'read']);

        return response()->json(['message' => 'success']);
    }

    public function markReadOne(Request $request){
        Message::where('id', $request->messageId)
               ->update(['status' => 'read']);
        
        return response()->json(['message' => 'success']);
    }

}

