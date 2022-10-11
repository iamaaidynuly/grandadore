<?php

namespace App\Services\Banners;
class BannerManager
{
    public static function widget(&$params, &$banners, $thisKey, $thisCount, $key, $label = null)
    {
        $data = explode('.', $key);
        $dataCount = count($data);
        if ($dataCount == 1) {
            $key = $thisKey;
            $count = $thisCount;
            $as = $data[0];
        } else if ($dataCount == 2) {
            $key = $data[0];
            $as = $data[1];
        } else {
            $key = $data[0];
            $i = $data[1];
            $as = $data[2];
        }
        if (empty($count)) $count = $i ?? 0;
        $config = [
            'key' => $key,
            'count' => $count,
            'as' => $as,
            'label' => $label,
            'params' => $params[$key]['params'][$as],
            'value' => $banners[$key][$count]['data'][$as] ?? null,
        ];

        return app('arrilot.widget')->run('Admin\BannerInput', $config);
    }
}
