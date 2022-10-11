<?php

namespace App\Models;

use App\Http\Traits\HasTranslations;
use App\Http\Traits\Sortable;
use App\Http\Traits\UrlUnique;
use App\Models\Criteria as Criteria;


class Filter extends AbstractModel
{
    use HasTranslations, UrlUnique, Sortable;

    protected $table = 'filters';
    public $translatable = ['name'];


    public function criteria()
    {
        return $this->hasMany('App\Models\Criteria', 'filter_id', 'id');
    }
//    public function category()
//    {
//        return $this->hasOne('App\Models\Category', 'id', 'cid');
//    }

    public function addFilter($request)
    {
        merge_model($request, $this, ['name']);
        //dd(merge_model($request, $this, ['name']));
        if (isset($request->status)) {
            $this->status = 1;
        }
        if ($this->save()) {
            $filterId = $this->id;
            $criteriaModel = new Criteria;
            if (!empty($request->input('criterion.new')) && count($request->input('criterion.new')) > 0) {
                $criteriaModel->addOrUpdate($filterId, $request);
            }

            return true;
        } else {
            return false;
        }
    }

    public static function editFilter($id, $request)
    {
        $filterModel = self::where('id', $id)->first();
        merge_model($request, $filterModel, ['name']);
        if (!empty($request->status)) {
            $filterModel->status = 1;
        } else {
            $filterModel->status = 0;
        }
        if ($filterModel->save()) {
            $criteriaModel = new Criteria;
            if (!empty($request->input('criterion.new'))) {
                if (count($request->input('criterion.new')) > 0 || count($request->input('criterion.old')) > 0) {
                    $criteriaModel->addOrUpdate($id, $request);
                }
            }

            return ['status' => true, 'category' => $filterModel->cid];
        }
        exit;
    }

    public static function deleteFilter($id)
    {
        self::where('id', $id)->delete();

        return true;
    }

    public static function getByCriteria($criteria)
    {
        $response = self::whereHas('criteria', function ($query) use ($criteria) {
            $query->whereIn('criteria.id', $criteria);
        })->with(['criteria' => function ($query) use ($criteria) {
            $query->whereIn('id', $criteria);
        }, 'category'])->get();

        return $response;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'filter_category');
    }
}
