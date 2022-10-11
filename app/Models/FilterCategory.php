<?php

namespace App\Models;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class FilterCategory extends AbstractModel
{
    protected $table = 'filter_category';

    public static function addOrEdit($category_id, $request)
    {
        self::where(['category_id' => $category_id])->delete();

        $insertData = [];
        if (!empty($request)) {
            foreach ($request as $filter) {

                $insertData[] = [
                    'category_id' => $category_id,
                    'filter_id' => $filter,
                ];
            }

            self::insert($insertData);
        }

        return true;
    }

    public function criteria()
    {
        return $this->hasMany('App\Models\Criteria', 'filter_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Category', 'id', 'category_id');
    }


}
