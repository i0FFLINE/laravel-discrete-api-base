<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\Organizations;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationCreateContract;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrganizationCreateAction extends OrganizationCreateContract
{
    public function do(User $User, array $input = []): JsonResponse|Response|null
    {
        if (!app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'o_title' => ['required', 'string', 'min:3', 'max:255'],
                'o_description' => ['string', 'string', 'max:16384'],
                'w_title' => ['required', 'string', 'min:3', 'max:255'],
                'w_description' => ['string', 'string', 'max:16384'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray(),], 404);
            }
            $Organization = DiscreteApiHelper::new_organization($User, ['title' => $input['o_title'], 'description' => $input['o_description']], ['title' => $input['w_title'], 'description' => $input['w_description']]);
            $Organization->load([
                'workspaces',
                'membership'
            ]);
            $User->profile->forceFill([
                'organization_id' => $Organization->id,
                'workspace_id' => $Organization->workspaces->first()->id,
            ])->save();
            return response()->noContent();
        }
        return null;
    }
}
