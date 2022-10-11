<?php

namespace App\Models;

use App\Services\Banners\BannerData;
use App\Services\Banners\BannerModel;
use Illuminate\Support\Facades\Cache;

class Banner extends AbstractModel
{
    public $timestamps = false;

    public static function cacheKey($page)
    {
        return 'banners_' . $page;
    }

    public static function get($page)
    {
        $cacheKey = self::cacheKey($page);
        if (Cache::has($cacheKey)) return Cache::get($cacheKey);
        $items = self::select('key', 'data')->where('page', $page)->sort()->get()->mapToGroups(function ($item) {
            return [$item['key'] => new BannerData($item['data'])];
        })->toArray();
        if (!empty($items)) {
            $result = [];
            foreach ($items as $key => $item) {
                $result[$key] = new BannerModel($item);
            }
            $result = (object)$result;
            Cache::forever($cacheKey, $result);

            return $result;
        }

        return null;

    }

    public static function getBanners($page)
    {
        return self::select('id', 'key', 'data')->where('page', $page)->sort()->get()->mapToGroups(function ($item) {
            return [
                $item['key'] => [
                    'id' => $item['id'],
                    'data' => json_decode($item['data'], true),
                ]
            ];
        });
    }

    public static function fixBanners($settings)
    {
        $pages = [];
        $allIds = [];
        foreach ($settings as $page => $keys) {
            $pages[] = $page;
            Cache::forget(self::cacheKey($page));
            $allKeys = [];
            foreach ($keys as $key => $data) {
                $allKeys[] = $key;
                $count = $data['count'] ?? 1;
                $ids = self::select('id')->where(['page' => $pages, 'key' => $key])->sort()->pluck('id')->toArray();
                $allIds = array_merge($allIds, array_slice($ids, $count));
            }
            self::where('page', $page)->whereNotIn('key', $allKeys)->delete();
        }
        self::whereNotIn('page', $pages)->orWhere(function ($q) use ($allIds) {
            $q->whereIn('id', $allIds);
        })->delete();

        return true;
    }

    public static function updateBanner($page, $key, $data, $id)
    {
        if ($id) return self::where(['id' => $id])->update(['data' => json($data)]);
        else return self::insert([
            'page' => $page,
            'key' => $key,
            'data' => json($data),
        ]);
    }

    public function scopeSort($q)
    {
        return $q->orderBy('id', 'asc');
    }
}
