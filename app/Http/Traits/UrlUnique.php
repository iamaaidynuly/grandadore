<?php

namespace App\Http\Traits;

use App\Services\Support\Str;

trait UrlUnique
{
    public static function url_unique($real_url, $ignore = false)
    {
        $url = Str::slug($real_url);
        $i = 1;
        $check_url_query = self::select('url')->where('url', 'regexp', '^' . preg_quote($real_url) . '(\-[1-9][0-9]*)?$');
        if ($ignore !== false) $check_url_query = $check_url_query->where('id', '<>', $ignore);
        $check_url = $check_url_query->pluck('url');
        while ($check_url->contains($url)) {
            $i++;
            $url = $real_url . '-' . $i;
        }

        return $url;
    }
}
