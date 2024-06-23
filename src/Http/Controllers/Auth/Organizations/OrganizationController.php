<?php

namespace IOF\DiscreteApi\Base\Http\Controllers\Auth\Organizations;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Http\Controllers\DiscreteApiController;
use IOF\DiscreteApi\Base\Models\Organization;

class OrganizationController extends DiscreteApiController
{
    public function __invoke(Request $request, string $id = null): JsonResponse|Response
    {
        if (!is_null($id) && !Str::isUuid($id)) {
            return response()->noContent(404);
        }
        if (!is_null($id)) {
            $Oo = Organization::with(['membership.user.profile'])->findOrFail($id);
            $roles = config('discreteapibase.organization.roles');
            foreach($Oo->membership as &$membership) {
                $membership->makeHidden(['organization_id']);
                $membership->role_title = trans(ucfirst($roles[$membership->role]));
            }
            return response()->json($Oo->toArray());
        }
        return response()->noContent(404);
    }
}
