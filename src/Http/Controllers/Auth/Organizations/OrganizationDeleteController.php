<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Organizations;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;
use IOF\DiscreteApi\Base\Models\Organization;
use IOF\DiscreteApi\Base\Models\OrganizationMember;

class OrganizationDeleteController extends DiscreteApiController
{
    public function __invoke(Request $request, string $id = null): JsonResponse|Response
    {
        if (!is_null($id) && !Str::isUuid($id)) {
            return response()->noContent(404);
        }
        if (!is_null($id)) {
            $Oo = Organization::with(['membership'])->findOrFail($id);
            OrganizationMember::whereIn('id', $Oo->membership->pluck('id'))->delete();
            $Oo->delete();
            return response()->noContent();
        }
        return response()->noContent(404);
    }
}
