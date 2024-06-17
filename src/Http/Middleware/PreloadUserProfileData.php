<?php

namespace IOF\DiscreteApi\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreloadUserProfileData
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (method_exists($request->user(), 'profile')) && config('discreteapibase.account.features.profile') === true) {
            $request->user()->load(['profile']);
        }

        return $next($request);
    }
}
