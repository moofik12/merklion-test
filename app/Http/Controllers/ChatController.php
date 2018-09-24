<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Service\User\UserChatEventFactory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function chatIndex()
    {
        return view('chat');
    }

    public function getUsers()
    {
        return User::where('is_online', true)->get();
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
     * @param UserChatEventFactory $userChatEventFactory
     */
    public function webhook(Request $request, UserChatEventFactory $userChatEventFactory)
    {
        $events = $request->get('events') ?? [];

        foreach ($events as $event) {
            $eventName = $event['name'];
            $userId = $event['user_id'];

            /** @var User $user */
            $user = User::find($userId);
            $event = $userChatEventFactory->create($eventName, $user);

            broadcast($event);
        }
    }
}