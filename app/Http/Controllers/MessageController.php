<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $message = new Message();
        $message->room_id = $request->room_id;
        $message->user_id = Auth::id();
        $message->body = $request->body;
        $message->save();


        $message = Message::with('user')->find($message->id);

        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message, 200);
    }

    public function getMessages($roomId)
    {
        $messages = Message::with('user')->where('room_id', $roomId)->latest()->paginate(15);

        return response()->json($messages, 200);
    }
}
