<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\Organizations;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationsContract;

class OrganizationsAction extends OrganizationsContract
{
    public function do(User $User): ?JsonResponse
    {
        if (!app()->runningInConsole()) {
            $oo = $User->organizations()->where('role', '>=', 0)->withCount(['members'])->get();
            $roles = config('discreteapibase.organization.roles');
            $oo->each(function (&$o) use ($roles) {
                $role = $o['pivot']['role'];
                if (in_array($role, array_keys($roles))) {
                    $o->role = trans($roles[$role]);
                } else {
                    $o->role = trans($roles[-1]);
                }
            });
            return response()->json($oo->toArray());
        }
        return null;
    }
}
