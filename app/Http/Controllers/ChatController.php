<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Service\Pusher\PusherMemberEventFactory;
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

    /**
     * @param Request $request
     * @param PusherMemberEventFactory $pusherMemberEventFactory
     */
    public function webhook(Request $request, PusherMemberEventFactory $pusherMemberEventFactory)
    {
        $events = $request->get('events');

        foreach ($events as $event) {
            $userId = $event['user_id'];
            $eventName = $event['name'];

            /** @var User $user */
            $user = User::find($userId);

            $event = $pusherMemberEventFactory->create($eventName, $user);

            broadcast($event);
        }
    }
}