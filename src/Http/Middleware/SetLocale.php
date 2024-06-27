<?php

namespace IOF\DiscreteApi\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiHelper;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(DiscreteApiHelper::compute_locale());
        return $next($request);
    }
}
