<?php

namespace TurFirst\Models;

use TurFirst\Http\Controllers\LanguageController;

class RoomsCategories extends AbstractModel
{
    //
    public function children()
    {
        return $this->hasMany(RoomsCategories::class, 'parent_id', 'id');
    }

    public function filters()
    {
        return $this->belongsToMany(RealEstatesFilter::class, 'real_estates_filter_to_category_relations', 'category_id', 'filter_id');

//        return $this->belongsToMany(RealEstatesFilter::class, 'real_estates_filter_to_category_relations', 'category_id', 'filter_id')->latest();
    }

    public function estates()
    {
        return $this->belongsToMany(Rooms::class, 'real_estates_to_category_relations', 'category_id', 'real_estate_id')->latest();
    }

    public function translate()
    {
        return $this->hasOne(RealEstatesCategoriesTranslate::class, 'category_id', 'id')->where('language_id', LanguageController::getLangId(app()->getLocale()));
    }

    public function translates()
    {
        return $this->hasMany(RealEstatesCategoriesTranslate::class, 'category_id', 'id');
    }

    public static function url_unique($real_url, $ignore = false)
    {
        $url = $real_url;
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

    public static function _save($request)
    {
        if ($request->category_id) {
            $category = self::find($request->category_id);
        } else {
            $category = new self();
        }
        if ($request->name_1) {
            $category->url = self::url_unique(to_url($request->name_1), $category->id);
        }
        if ($request->status) {
            $category->status = $request->status;
        } else {
            $category->status = 0;
        }
        if ($request->parent_id) {
            $category->parent_id = $request->parent_id;
        } else {
            $category->parent_id = null;
        }
        $category->save();

        return $category;
    }

    public function removeParentBeforeDelete($id)
    {
        $categories = $this->where('parent_id', $id)->get();
        foreach ($categories as $cat) {
            $cat->parent_id = null;
            $cat->save();
        }
    }

}
