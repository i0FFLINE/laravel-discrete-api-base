<?php

namespace IOF\DiscreteApi\Base\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Models\Organization;
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
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        $parsed = parse_url($url);
        if (!empty($parsed['query'])) {
            parse_str($parsed['query'], $query);
        } else {
            $query = null;
        }
        if (!empty($query)) {
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
        if (!empty($p['user'])) {
            $return .= $p['user'];
            if (!empty($p['pass'])) {
                $return .= ':' . $p[''] . '@';
            } else {
                $return .= '@';
            }
        }
        $return .= $p['host'];
        if (!empty($p['port'])) {
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
        if (!empty($p['fragment'])) {
            $return .= '#' . $p['fragment'];
        }
        return $return;
    }

    public static function new_organization(User $User, array $o = [], array $w = []): ?Organization
    {
        if (config('discreteapibase.features.organizations') === true) {
            $Organization = Organization::create([
                Sorter::FIELD => $User->organizations()->count() + 1,
                'title' => empty($o['title']) ? trans('Personal organization') : $o['title'],
                'description' => empty($o['description']) ? trans('Your personal organization, but you can invite new members into the organization to share content.') : $o['description'],
                'owner_id' => $User->id,
            ]);
            if (!is_null($Organization)) {
                $Organization->membership()->create([
                    'user_id' => $User->id,
                    'role' => 10,
                    'updated_by' => $User->id,
                ]);
                $Organization->workspaces()->create([
                    Sorter::FIELD => 1,
                    'title' => empty($w['title']) ? trans('Personal workspace') : $w['title'],
                    'description' => empty($w['description']) ? trans('Your personal organization, but you can invite new members into the organization to share content.') : $w['description'],
                ]);
            }
            return $Organization;
        }
        return null;
    }

    public static function in_organization(User $User, Organization $Organization): bool
    {
        if ($Organization->owner_id == $User->id) {
            return true;
        }
        if ($Organization->members()->where('user_id', $User->id)->where('role', '>=', 0)->count()) {
            return true;
        }
        return false;
    }

    public static function organization_member_role(User $User, Organization $Organization): ?int
    {
        if ($Organization->owner_id == $User->id) {
            return 10;
        }
        if ($Membership = $Organization->members()->where('user_id', $User->id)->first()) {
            return $Membership->role;
        }
        return null;
    }
}
