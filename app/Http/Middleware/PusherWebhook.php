<?php

namespace App\Http\Middleware;

use App\Service\Pusher\SignatureChecker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class PusherWebhook
{
    /**
     * Handle an incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $secret = env('PUSHER_APP_SECRET');
        $signature = $request->header('X_PUSHER_SIGNATURE');
        $signatureChecker = new SignatureChecker($secret, $signature, $request->all());

        if (!$signatureChecker->isValid()) {
            throw new PreconditionFailedHttpException('invalid_webhook_signature');
        }

        return $next($request);
    }
}