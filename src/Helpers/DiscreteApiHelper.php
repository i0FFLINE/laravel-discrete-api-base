<?php

namespace IOF\DiscreteApi\Base\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
}
