<?php

namespace App\Http\Controllers;

class ChatController extends Controller
{
    public function chatIndex()
    {
        return view('chat');
    }
//
//    public function sendMessage(Request $request, Dispatcher $dispatcher)
//    {
//        $input = $request->all();
//        $message = Message::create($input);
//
//        $messageReceivedEvent = new MessageReceived($message);
//        $dispatcher->dispatch($messageReceivedEvent);
//    }
}