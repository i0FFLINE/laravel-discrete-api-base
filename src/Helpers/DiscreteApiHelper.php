<?php

namespace IOF\DiscreteApi\Base\Helpers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use IOF\DiscreteApi\Base\Models\Organization;
use IOF\DiscreteApi\Base\Models\Profile;
use IOF\Utils\Sorter;

class DiscreteApiHelper
{
    public static function generate_unique_public_user_name(): string
    {
        $last = last(explode("-", Str::uuid()));
        $Validator = Validator::make(['last' => $last], [
            'last' => ['required', 'string', 'max:12', 'min:12', 'unique:users,public_name'],
        ]);
        if ($Validator->fails()) {
            return static::generate_unique_public_user_name();
        }
        return $last;
    }

    public static function detail_url(string $url = null): ?array
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        $parsed = parse_url($url);
        if (! empty($parsed['query'])) {
            parse_str($parsed['query'], $query);
        } else {
            $query = null;
        }
        if (! empty($query)) {
            $parsed['query'] = $query;
        }
        return $parsed;
    }

    public static function join_detailed_url(array $p = []): ?string
    {
        if (empty($p['scheme']) || empty($p['host']) || empty($p['path']) || empty($p['query'])) {
            return null;
        }
        $return = $p['scheme'] . '://';
        if (! empty($p['user'])) {
            $return .= $p['user'];
            if (! empty($p['pass'])) {
                $return .= ':' . $p[''] . '@';
            } else {
                $return .= '@';
            }
        }
        $return .= $p['host'];
        if (! empty($p['port'])) {
            $return .= ':' . $p['port'];
        }
        $return .= $p['path'];
        $x = false;
        $return .= '?';
        foreach ($p['query'] as $_q => $_v) {
            if ($x) {
                $return .= "&";
            }
            $return .= $_q . '=' . $_v;
            $x = true;
        }
        if (! empty($p['fragment'])) {
            $return .= '#' . $p['fragment'];
        }
        return $return;
    }

    public static function new_organization(User $User, array $o = []): ?Organization
    {
        if (config('discreteapibase.features.organizations') === true) {
            $Organization = Organization::create([
                Sorter::FIELD => $User->organizations()->count() + 1,
                'title' => empty($o['title']) ? trans('Personal organization') : $o['title'],
                'description' => empty($o['description']) ? trans('Your personal organization, but you can invite new members into the organization to share content.') : $o['description'],
                'owner_id' => $User->id,
            ]);
            if (! is_null($Organization)) {
                $Organization->membership()->create([
                    'user_id' => $User->id,
                    'role' => 10,
                    'updated_by' => $User->id,
                ]);
            }
            return $Organization;
        }
        return null;
    }

    public static function in_organization(User $User, ?Organization $Organization = null): bool
    {
        if (is_null($Organization)) {
            return -2;
        }
        if ($Organization->owner_id == $User->id) {
            return true;
        }
        if ($Organization->members()->where('user_id', $User->id)->where('role', '>=', 0)->count()) {
            return true;
        }
        return false;
    }

    public static function organization_member_role(User $User, ?Organization $Organization = null): ?int
    {
        if (is_null($Organization)) {
            return -2;
        }
        if ($Organization->owner_id == $User->id) {
            return 10;
        }
        return ($Membership = $Organization->members()->where('user_id', $User->id)->first()) ? $Membership->role : null;
    }

    public static function create_user_profile(User $User, array $input = []): Model|Profile
    {
        if (! is_null($User->profile)) {
            return $User->profile;
        }

        $org_field = config('discreteapibase.organization.singular_name') . '_id';
        $arr_norm = ['locale', $org_field];
        $arr_input = array_keys($input);
        sort($arr_norm);
        sort($arr_input);
        if ($arr_norm !== $arr_input) {
            $input = array_unique(array_intersect($arr_input, $arr_norm));
        }
        $Validator = Validator::make($input, [
            'locale' => [
                'string',
                'min:2',
                'max:2',
                Rule::in(array_keys(config('discreteapibase.locales')))
            ],
            $org_field => ['uuid'],
        ]);
        if ($Validator->fails()) {
            return $User->profile()->create();
        }
        return $User->profile()->create($input);
    }

    public static function compute_locale(): string
    {
        $hl = request()->headers->get('Accept-Language', 'en');
        if (auth()->check()) {
            if (! is_null(request()->user()->profile) && ! is_null(request()->user()->profile->locale) && in_array(request()->user()->profile->locale, array_keys(config('discreteapibase.locales')))) {
                if (request()->user()->profile->locale != $hl) {
                    request()->user()->profile->forceFill(['locale' => $hl])->save();
                    return $hl;
                }
                return request()->user()->profile->locale;
            } elseif (! is_null($hl) && in_array($hl, array_keys(config('discreteapibase.locales')))) {
                return $hl;
            }
        } elseif (! is_null(request()->headers->get('Accept-Language', 'en')) && in_array(request()->headers->get('Accept-Language', 'en'), array_keys(config('discreteapibase.locales')))) {
            return request()->headers->get('Accept-Language', 'en');
        }
        return config('app.locale');
    }
}
