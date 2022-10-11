<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class ReviewItem extends AbstractModel
{
    public $timestamps = false;
    protected $table = 'review_item';

    private static function cacheKey()
    {
        return 'review_item';
    }


    public static function clearCaches()
    {
        Cache::forget(self::cacheKey());
    }


    public static function action($id, $data)
    {
        self::clearCaches();
        $review_item = new self();
        $review_item->item_id = $data['item_id'];
        $review_item->user_id = $data['user_id'];
        $review_item->review_id = $id;
        $review_item->save();
    }


    public function reviews()
    {
        return $this->HasOne('App\Models\Reviews', 'id', 'review_id')->where('moderated', 1);
    }

    public function user()
    {
        return $this->HasOne('App\Models\User', 'id', 'user_id');
    }

}
