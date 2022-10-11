<?php

namespace App\Models;


use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;

class ColorFilter extends AbstractModel
{
    use HasTranslations, Sortable;

    public $translatable = ['name'];

    protected $fillable = [
        'name', 'hex_color'
    ];

    public function addFilter(array $data)
    {
        $filter = new static();

        $filter->setTranslation('name', 'ru', $data['name'] ?? '');
        $filter->hex_color = $data['hex_color'];

        return $filter->save();
    }

    public static function editFilter($id, array $data)
    {
        $model = self::query()->where('id', $id)->first();

        $model->setTranslation('name', 'ru', $data['name'] ?? '');
        $model->hex_color = $data['hex_color'];

        return $model->save();
    }

    public function items()
    {
        return $this->belongsToMany(Items::class, 'color_filter_relations', 'filter_id', 'item_id');
    }

}
