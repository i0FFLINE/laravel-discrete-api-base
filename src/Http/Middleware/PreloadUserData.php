<?php

namespace IOF\DiscreteApi\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreloadUserData
{
    public function handle(Request $request, Closure $next): Response
    {
        $load = [];
        if (auth()->check()) {
            if ((method_exists($request->user(), 'profile')) && config('discreteapibase.features.profile') === true) {
                $load[] = 'profile';
                if ((method_exists($request->user()->profile, 'organization')) && config('discreteapibase.features.organizations') === true) {
                    $load[] = 'profile.organization';
                    $load[] = 'profile.workspace';
                }
            }
            if ((method_exists($request->user(), 'organizations')) && config('discreteapibase.features.organizations') === true) {
                $load[] = 'organizations';
            }
        }
        if (!empty($load)) {
            $request->user()->load($load);
        }

        return $next($request);
    }
}
