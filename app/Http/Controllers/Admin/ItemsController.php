<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use App\Models\BrandsItems;


use App\Models\Category;
use App\Models\ColorFilter;
use App\Models\CompanyItems;
use App\Models\Filter;
use App\Models\FilterCategory;
use App\Models\ItemCategories;
use App\Models\ItemCriterions;
use App\Models\ItemOptions;
use App\Models\Items;
use App\Models\ItemSizes;
use App\Models\ItemStatus;
use App\Models\User;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Validator;


class ItemsController extends BaseController
{


    /**
     * Tours list page
     * @param items $tours
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id = null)
    {
        if (!empty($id)) {
            $items_ids = CompanyItems::where('user_id', $id)->pluck('item_id')->toArray();
            $company = User::where('type', 1)->where('admin', 0)->where('id', $id)->firstOrFail();
            $items = Items::whereIn('id', $items_ids)->with("reviews")->sort()->get();

            return view('admin.pages.items.index', compact('items', 'company'));
        }
        $moderated_items = Items::adminList()->where('moderated', 1);
        /*foreach ($moderated_items as $item){
            echo "<pre>"; print_r($item->title);
        }
        exit();
        dd($moderated_items);*/

        return view('admin.pages.items.index', compact('moderated_items'));
    }

    public function ModerateItem($id, $page = null)
    {
        $item = Items::where('id', $id)->firstOrFail();
        if ($item->moderated) {
            $item->moderated = 0;
        } else {
            $item->moderated = 1;
        }
        $item->save();
        if (empty($page)) {
            $page = 1;
        }

        return \redirect()->back()->withInput(['page' => $page]);
    }

    public function ModerateMany(Request $request)
    {
        $ids = $request->moderate;
        Items::whereIn('id', $ids)->update(['moderated' => 1]);

        return \redirect()->back();
    }

    public function addItem()
    {
        $data['categories'] = Category::where('deep', 0)->with('childrens')->get();
        $data['item_criterions'] = Filter::with('criteria')->get();
        $data['brands'] = Brands::where('active', 1)->get();

        return view('admin.pages.items.form', $data);
    }

    public function sort()
    {
        return items::sortable();
    }

    public function addSave(Request $request)
    {
        $item = Items::where('code', lower_case($request->code))->orWhere('code', strtoupper($request->code))->orWhere('code', $request->code)->first();
        if (!empty($item)) {
            $item_id = $item->id;
            $brand_item = BrandsItems::where(['item_id' => $item_id])->first();
            if (!empty($brand_item)) {
                Notify::error('Товар с таким кодом уже существует');

                return redirect()->back()->withInput();
            }
        }
        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (items::action(null, $validator['inputs'])) {
            Notify::success('Товар успешно добавлен.');

            return redirect()->route('admin.items.index');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function itemFilters($id)
    {
        $category_id = ItemCategories::where('item_id', $id)->first()->category_id;
        $category = Category::where('id', $category_id)->first();
        foreach ($category->onlyParents() as $category) {
            $categories_ids[] = $category->id;
        }
        $categories_ids[] = (int)$id;

        $filters_ids = FilterCategory::whereIn('category_id', $categories_ids)->groupBy('filter_id')->pluck('filter_id');

        $data['item_criterions'] = Filter::whereIn('id', $filters_ids ?? [])->with('criteria')->get();
        $data['item_id'] = $id;
        $data['item_criterions_array'] = ItemCriterions::where('item_id', $id)->pluck('criteria_id')->toArray();
        $data['selectedColorFilters'] = Items::query()->where('id', $id)->firstOrFail()->colorFilters()->pluck('color_filters.id')->toArray();
        $data['colorFilters'] = ColorFilter::query()->sort()->get();

        return view('admin.pages.items.filtersItem', $data);
    }

    public function itemCriterionSubmit($item_id, Request $request)
    {
        if (!empty($request->item_criterions)) {
            ItemCriterions::action($item_id, $request->item_criterions);
        } else {
            ItemCriterions::action($item_id, []);
        }

        $item = Items::query()->where('id', $item_id)->first();

        if ($colorFilters = $request->input('color_filters')) {
            $item->colorFilters()->sync($colorFilters);
        } else {
            $item->colorFilters()->detach();
        }

        return redirect()->back();
    }

    public function editItem($id)
    {
        $categories = Category::where('deep', 0)->with('childrens')->get();
        $options = ItemOptions::where('item_id', $id)->get();
        $sizes = ItemSizes::where('item_id', $id)->get();
        $items_category = ItemCategories::where('item_id', $id)->get()->pluck('category_id')->toArray();
        $item_criterions = Filter::with('criteria')->get();
        $item_criterions_array = ItemCriterions::where('item_id', $id)->pluck('criteria_id')->toArray();
        $item_brands = BrandsItems::where('item_id', $id)->first();
        $brands = Brands::where('active', 1)->get();

        $item = Items::where('id', $id)->first();
        $itemStatusAll = ItemStatus::query()->where('item_id',$id)->get();
$arrayStatus = [10,11];
        foreach ($itemStatusAll as $status){
            $arrayStatus[] = $status['status'];
        }


        return view('admin.pages.items.edit', compact('item', 'categories', 'items_category', 'brands', 'item_brands', 'options', 'sizes', 'item_criterions', 'item_criterions_array','arrayStatus'));
    }

    public function EditSave($id, Request $request)
    {
        $items = Items::where('code', lower_case($request->code))->orWhere('code', strtoupper($request->code))->orWhere('code', $request->code)->first();
        if (!empty($items) && $items->id != $id) {
            $item_id = $items->id;
            $brand_item = BrandsItems::where(['item_id' => $item_id, 'brand_id' => $request->brands])->first();
            if (!empty($brand_item)) {
                Notify::error('Товар с таким кодом уже существует');

                return redirect()->back()->withInput();
            }
        }
        $item = items::getItem($id);

        $validator = $this->validator($request, $id);
        $validator['validator']->validate();


        if (Items::action($item, $validator['inputs'])) {
            Notify::success('Товар успешно редактирован');

            return redirect()->route('admin.items.edit', ['id' => $id]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        if ($id && is_id($id)) {
            $item = Items::where('id', $id)->first();

            if ($item && items::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($request, $ignore = false)
    {
        $inputs = $request->all();
        if (!empty($inputs['url'])) $inputs['url'] = lower_case($inputs['url']);
        $inputs['generated_url'] = !empty($inputs['title'][$this->urlLang]) ? to_url($inputs['title'][$this->urlLang]) : null;
        $request->merge(['url' => $inputs['url']]);
        $unique = $ignore === false ? null : ',' . $ignore;
        $result = [];
        $rules = [

            'generated_url' => 'required_with:generate_url|string|nullable',
            'categories' => 'int|required',
            'criterion' => 'array',
            'code' => 'required|string|max:255',
            'count' => 'required|int|max:1000000000',
            'price' => 'nullable|int|max:1000000000',
            'delivery_price' => 'int|max:100|min:1|nullable',
            'sizes.new.*.price' => 'numeric'
        ];
        if (empty($inputs['generate_url'])) {
            $rules['url'] = 'required|is_url|string|unique:items,url' . $unique . '|nullable';
        }
        if (!$ignore) {
            $rules['image'] = 'nullable|image';
        } else {
            $rules['image'] = 'nullable|image';
        }
        $result['validator'] = \Illuminate\Support\Facades\Validator::make($inputs, $rules, [
            'generated_url.required_with' => 'Введите название (' . $this->urlLang . ') чтобы сгенерировать URL.',
            'url.required' => 'Введите URL или подставьте галочку "сгенерировать автоматический".',
            'categories.required' => 'Связать с категорией обязательно".',
            'url.is_url' => 'Неправильный URL.',
            'url.unique' => 'URL уже используется.',
            'image.image' => 'Неверное Изображение.',
            'image.required' => 'Выберите Изображение.',
            'code.required' => 'Ввведите код товара  ',
            'count.required' => 'Ввведите количество товара',
            'delivery_price.max' => 'Процент скидки от 1 до 100',
            'delivery_price.min' => 'Процент скидки от 1 до 100',
            'delivery_price.int' => 'Процент скидки от 1 до 100',
            'price.int' => 'Цена должен быть цифрой',
            'numeric' => 'Цена размера должна быть цифрой'
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }


    public function itemChangeStatus(Request $request){
        $itemStatusAll=ItemStatus::where('item_id',$request->id)->get();
        if(!empty($itemStatusAll)){
            foreach ($itemStatusAll as $item){
                $item->delete();
            }
        }

        foreach ($request->status as $status){
            $itemStatus= new ItemStatus ;
            $itemStatus->status = (int)$status;
            $itemStatus->item_id = (int)$request->id;
            $itemStatus->save();
        }
        return response()->json(['success'=>true]);
    }
}




