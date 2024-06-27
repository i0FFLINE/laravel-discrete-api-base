<?php

namespace IOF\DiscreteApi\Base\Actions\Auth\Organizations;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use IOF\DiscreteApi\Base\Contracts\Auth\Organizations\OrganizationUpdateContract;
use IOF\DiscreteApi\Base\Models\Organization;

class OrganizationUpdateAction extends OrganizationUpdateContract
{
    public function do(User $User, Organization $Organization, array $input = []): Response|JsonResponse|null
    {
        if (!app()->runningInConsole()) {
            $Validator = Validator::make($input, [
                'title' => ['required', 'string', 'min:3', 'max:255'],
                'description' => ['string', 'string', 'max:16384'],
            ]);
            if ($Validator->fails()) {
                return response()->json(['errors' => $Validator->errors()->toArray(),], 404);
            }
            $Organization->forceFill([
                'title' => $input['title'],
                'description' => $input['description'],
            ])->save();
            $Organization->load(['membership']);
            return response()->noContent();
        }
        return null;
    }
}
