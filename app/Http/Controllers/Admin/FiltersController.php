<?php

namespace App\Http\Controllers\Admin;

use App\Models\Criteria;
use App\Models\Filter;
use App\Models\FilterCategory;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;

class FiltersController extends BaseController
{
    public function filtersList()
    {
        $pageData['filters'] = Filter::get();

        return view('admin.pages.filters.list', $pageData);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                //'name' => 'string|required|max:255',
                'criterion' => 'required|array',
            ]);

            $filtersModel = new Filter;
            $response = $filtersModel->addFilter($request);
            if ($response) {
                Notify::success('Фильтр добавлен', $title = null, $options = []);
            } else {
                Notify::error('Произошла ошибка', $title = null, $options = []);
            }
        }

        return view('admin.pages.filters.add');
    }


    public function update($id, Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                //'name' => 'string|required|max:255',
                'criterion' => 'array'
            ]);
            $response = Filter::editFilter($id, $request);
            if ($response['status']) {
                Notify::success('Изменения сохранены', $title = null, $options = []);

                return redirect()->route('admin.filters.list');
            } else {
                Notify::error('Произошла ошибка', $title = null, $options = []);
            }
        }

        $pageData['filter'] = Filter::where('id', $id)->with(['criteria'])->firstOrFail();
        //dd($pageData['filter']);

        return view('admin.pages.filters.edit', $pageData);
    }

    public function delete($id)
    {
        if (Filter::deleteFilter($id)) {
            FilterCategory::where('filter_id', $id)->delete();
        }
        echo true;
    }

    public function deleteCriterion($id)
    {
        Criteria::where(['id' => $id])->delete();
        echo true;
    }

    public function getFilters(Request $request)
    {
        if ($request->has('categories')) {
            echo json_encode(Filter::whereIn('cid', $request->input('categories'))->with(['criteria'])->get()->toArray());
        }
    }
}
