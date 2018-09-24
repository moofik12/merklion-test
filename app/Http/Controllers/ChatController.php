<?php

namespace App\Http\Controllers;

use App\Events\AvatarChanged;
use App\Events\MessageSent;
use App\Events\UserJoined;
use App\Events\UserLeft;
use App\Http\Requests\UploadImageRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManager;

class ChatController extends Controller
{
    /**
     * @param Guard $guard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chatIndex(Guard $guard)
    {
        /** @var User $user */
        $user = $guard->user();
        $event = new UserJoined($user);

        broadcast($event)->toOthers();

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
     * @param ImageManager $imageManager
     *
     * @return JsonResponse
     */
    public function uploadAvatar(UploadImageRequest $request, Guard $guard, ImageManager $imageManager)
    {
        /** @var User $user */
        $user = $guard->user();
        $imageData = $request->get('image');

        $mimeType = explode(':', substr($imageData, 0, strpos($imageData, ';')))[1];
        $dataType =  explode('/', $mimeType)[1];
        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $dataType;

        $relativePathToImage = 'images/' . $fileName;
        $absolutePathToImage = public_path('images/') . $fileName;
        $imageManager->make($imageData)->save($absolutePathToImage);

        $avatarChangedEvent = new AvatarChanged($user, $relativePathToImage);
        broadcast($avatarChangedEvent);

        return response()->json(['error' => false]);
    }

    /**
     * @param Request $request
     */
    public function webhook(Request $request)
    {
        $events = $request->get('events') ?? [];

        foreach ($events as $event) {
            $eventName = $event['name'];
            $userId = $event['user_id'];

            if ('member_removed' === $eventName) {
                /** @var User $user */
                $user = User::find($userId);
                $event = new UserLeft($user);
            }

            broadcast($event)->toOthers();
        }
    }
}