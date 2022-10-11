<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\UrlUnique;

class Support extends AbstractModel
{
    use HasTranslations, UrlUnique;

    public $translatable = ['title', 'answer'];
    public $timestamps = false;


    public static function adminList()
    {
        return self::select('id', 'title', 'answer', 'active', 'created_at')->sort()->get();
    }


    public static function action($model, $inputs)
    {

        if (empty($model)) {
            $model = new self;
            $action = 'add';
            $ignore = false;
        } else {
            $action = 'edit';
            $ignore = $model->id;
        }
        $model['active'] = (int)!empty($inputs['active']);

        merge_model($inputs, $model, ['title', 'answer']);


        return $model->save();
    }


    public static function deleteItem($model)
    {
        return $model->delete();
    }
}
