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
            if ((method_exists($request->user(), 'profile')) && config('discreteapibase.features.profile')) {
                $load[] = 'profile';
                if (method_exists($request->user()->profile, config('discreteapibase.organization.singular_name')) && config('discreteapibase.features.organizations', true)) {
                    $load[] = 'profile.' . config('discreteapibase.organization.singular_name');
                }
            }
            if ((method_exists($request->user(), config('discreteapibase.organization.plural_name'))) && config('discreteapibase.features.organizations', true)) {
                $load[config('discreteapibase.organization.plural_name')] = function ($q) {
                    return $q->withCount(['members']);
                };
            }
            //dd($load);
        }
        if (!empty($load)) {
            $request->user()->load($load);
        }

        return $next($request);
    }
}
