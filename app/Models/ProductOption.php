<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;
use Illuminate\Support\Facades\File;

class ProductOption extends AbstractModel
{
    use HasTranslations, Sortable;

    public $translatable = ['title'];
    protected $sortableDesc = false;

    public static function adminList()
    {
        return self::sort()->get();
    }

    public static function action($model, $inputs)
    {
        if (empty($model)) {
            $model = new self;
            $edit = false;
            $model['sort'] = $model->sortValue();
        } else {
            $edit = true;
        }
        $model['active'] = (int)!empty($inputs['active']);
        merge_model($inputs, $model, ['title']);


        if ($image = upload_original_image('image', 'u/product_options/', ($edit && !empty($model->image)) ? $model->image : false)) $model['image'] = $image;

        return $model->save();
    }

    public static function getIds($ids)
    {
        return self::whereIn('id', $ids)->pluck('id');
    }

    public static function deleteItem($item)
    {
        if ($item->image) {
            File::delete(public_path('u/product_options/' . $item->image));
        }
        $item->products()->detach();

        return $item->delete();
    }

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'options_to_products', 'option_id', 'product_id')->sort();
    }
}
