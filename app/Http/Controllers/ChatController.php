<?php

namespace App\Http\Controllers;

use App\Events\AvatarChanged;
use App\Events\MessageSent;
use App\Http\Requests\UploadImageRequest;
use App\Service\User\UserChatEventFactory;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;

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
     * @return Collection
     */
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
     * @param UploadImageRequest $request
     * @param Guard $guard
     *
     * @return JsonResponse
     */
    public function uploadAvatar(UploadImageRequest $request, Guard $guard)
    {
        /** @var User $user */
        $user = $guard->user();
        $imageData = $request->get('image');

        $mimeType = explode(':', substr($imageData, 0, strpos($imageData, ';')))[1];
        $dataType =  explode('/', $mimeType)[1];
        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $dataType;

        $relativePathToImage = 'images/' . $fileName;
        $absolutePathToImage = public_path('images/') . $fileName;
        Image::make($request->get('image'))->save($absolutePathToImage);

        $avatarChangedEvent = new AvatarChanged($user, $relativePathToImage);
        broadcast($avatarChangedEvent);

        return response()->json(['error' => false]);
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

            broadcast($event)->toOthers();
        }
    }
}