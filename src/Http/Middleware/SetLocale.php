<?php

namespace IOF\DiscreteApi\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $headers_locale = $request->headers->get('Accept-Language', 'en');
        if (auth()->check()) {
            if (!is_null($request->user()->profile) && !is_null($request->user()->profile->locale) && in_array($request->user()->profile->locale, array_keys(config('discreteapibase.locales')))) {
                app()->setLocale($request->user()->profile->locale);
            } elseif (!is_null($headers_locale) && in_array($headers_locale, array_keys(config('discreteapibase.locales')))) {
                app()->setLocale($headers_locale);
            }
        } elseif (!is_null($headers_locale) && in_array($headers_locale, array_keys(config('discreteapibase.locales')))) {
            app()->setLocale($headers_locale);
        }
        return $next($request);
    }
}
