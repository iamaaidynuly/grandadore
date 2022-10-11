<?php

namespace App\Http\Controllers\Admin;

use App\Models\Filter;
use App\Models\FilterCategory;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;

class FilterCategoryController extends BaseController
{
    public function filterCategory($id, Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'filters_id' => 'array'
            ]);
            $response = FilterCategory::addOrEdit($id, $request->filters_ids);
            if ($response) {
                Notify::success('Изменения сохранены', $title = null, $options = []);

                return redirect()->route('admin.filters.filterCategory', ['id' => $id]);
            } else {
                Notify::error('Произошла ошибка', $title = null, $options = []);
            }
        }
        $pageData['filters'] = Filter::where('status', 1)->get();
        $pageData['filterCategory'] = FilterCategory::where('category_id', $id)->get()->pluck('filter_id')->toArray();
        $pageData['id'] = $id;

        return view('admin.pages.category.filterCategory', $pageData);

    }
}
