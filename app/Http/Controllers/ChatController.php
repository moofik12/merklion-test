<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Events\Dispatcher;
use App\Message;
use App\User;
use Pusher\PusherInstance;

class ChatController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function chatIndex()
    {
        return view('chat');
    }

    /**
     * @return JsonResponse
     */
    public function getMessages()
    {
        return Message::take(5)
            ->orderBy('id', 'DESC')
            ->with('user')
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * @param Request $request
     * @param Dispatcher $dispatcher
     * @param Guard $guard
     *
     * @return array
     */
    public function sendMessage(Request $request, Guard $guard)
    {
        /** @var User $user */
        $user = $guard->user();

        /** @var Message $message */
        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        $messageSentEvent = new MessageSent($user, $message);

        broadcast($messageSentEvent)->toOthers();

        return ['status' => 'Message Sent!'];
    }
}