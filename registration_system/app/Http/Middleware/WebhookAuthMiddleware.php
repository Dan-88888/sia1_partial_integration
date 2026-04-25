<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebhookAuthMiddleware
{
    /**
     * Validate incoming webhook requests using a shared API key.
     * The key must be sent in the X-API-Key header.
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        $validKey = config('services.admission.webhook_key');

        if (!$validKey || $apiKey !== $validKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
