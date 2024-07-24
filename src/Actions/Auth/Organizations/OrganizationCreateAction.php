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
                'title' => ['required', 'string', 'min:3', 'max:255'],
                'description' => ['string', 'string', 'max:16384'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray(),], 404);
            }
            $Organization = DiscreteApiHelper::new_organization($User, ['title' => $input['title'], 'description' => $input['description']]);
            $Organization->load(['membership']);
            $User->profile->forceFill([
                'organization_id' => $Organization->id,
            ])->save();
            return response()->json($Organization->toArray());
        }
        return null;
    }
}
