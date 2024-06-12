<?php

namespace IOF\DiscreteApi\Base\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class DiscreteApiController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
