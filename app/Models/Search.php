<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;
use App\Http\Traits\UrlUnique;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Search extends AbstractModel
{
    use Sortable, HasTranslations, UrlUnique;

    protected $sortableDesc = false;
    protected $fillable = ['title', 'active'];
    public $translatable = ['title'];


    public static function action($model, $inputs)
    {
        if (empty($model)) {
            $model = new self;
            $model['active'] = 1 ;
        }

        merge_model($inputs, $model, ['title']);

        return $model->save();
    }

    public static function homeList()
    {
            return self::select('title', 'created_at')->where('active', 1)->get();
    }


    public static function adminList()
    {
        return self::sort()->get();
    }

    public static function siteList()
    {
        return self::where('active', 1)->sort()->get();
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public static function deleteItem($model)
    {
        return $model->delete();
    }
}
