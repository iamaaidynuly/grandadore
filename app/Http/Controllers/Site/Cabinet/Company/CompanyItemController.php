<?php

namespace App\Http\Controllers\Site\Cabinet\Company;

use App\Exports\UsersExport;
use App\Http\Controllers\Site\BaseController;
use App\Models\Category;
use App\Models\CompanyItems;
use App\Models\CompanyPackages;
use App\Models\Filter;
use App\Models\FilterCategory;
use App\Models\ItemCategories;
use App\Models\ItemCriterions;
use App\Models\ItemOptions;
use App\Models\Items;
use App\Models\Packages;
use App\Models\User;
use App\Services\Notify\Facades\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;

class   CompanyItemController extends BaseController
{

    public function list()
    {

        $data = [];
        $data['user'] = User::auth();
        $data['title'] = 'asas';
        $data['current_page'] = 111;
        $data['items'] = CompanyItems::where('user_id', \auth()->user()->id)->with('items')->get();

        return view('site.pages.cabinet.company.items.list', $data);
    }


    public function itemFilters($id)
    {
        $item = Items::getItem($id);
        $data['role'] = true;
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

        return view('site.pages.cabinet.company.items.filtersItem', $data);
    }

    public function itemCriterionSubmit($item_id, Request $request)
    {

        if (!empty($request->item_criterions)) {
            ItemCriterions::action($item_id, $request->item_criterions);

            return \redirect()->back();

        } else {
            ItemCriterions::action($item_id, []);

            return \redirect()->back();
        }

    }


    public function add()
    {
        $company = User::where(['id' => auth()->user()->id, 'type' => 1, 'admin' => 0])->firstOrFail();

        $company_package = CompanyPackages::where('created_at', '>', Carbon::now()->subMonth(1)->toDateTimeString())->where(['status' => 1, 'company_id' => $company->id])->with(['package', 'company'])->first();
        if (empty($company_package)) {
            $package = Packages::where('id', 1)->firstOrFail();
        } else {
            $package = $company_package->package[0];
        }
        $company_items_count = CompanyItems::where('user_id', $company->id)->get()->count();

        if ($company_items_count >= (int)$package->count_products) {
            return redirect()->back()->withErrors(['error' => 'Превышен лимит допустимых товаров']);
        }


        $data = [];
        $data['role'] = true;
        $data['user'] = User::auth();
        $data['title'] = 'asas';
        $data['current_page'] = 111;
        $data['categories_company'] = Category::with('childrens')->get();
        $data['item_criterions'] = Filter::with('criteria')->get();

        return view('site.pages.cabinet.company.items.add', $data);
    }

    public function edit($id)
    {

        $data = [];
        $data['role'] = true;
        $data['options'] = ItemOptions::where('item_id', $id)->get();
        $data['current_page'] = 111;
        $data['item'] = Items::where('id', $id)->firstOrFail();
        $data["items_category"] = ItemCategories::where('item_id', $id)->get()->pluck('category_id')->toArray();
        $data["item_criterions_array"] = ItemCriterions::where('item_id', $id)->pluck('criteria_id')->toArray();
        $data['categories_company'] = Category::with('childrens')->get();
        $data['item_criterions'] = Filter::with('criteria')->get();

        return view('site.pages.cabinet.company.items.edit', $data);
    }

    public function add_put(Request $request)
    {
        $user = User::where('id', $request->company)->where('type', 1)->firstOrfail();
        $company_itemsIds = User::where(['id' => auth()->user()->id, 'type' => 1, 'admin' => 0])->firstOrFail();
        $item = Items::where('code', lower_case($request->code))->orWhere('code', $request->code)->orWhere('code', strtoupper($request->code))->whereIn('id', $company_itemsIds)->first();

        if (!empty($item)) {
            $item_id = $item->id;
            $company_item = CompanyItems::where(['item_id' => $item_id, 'user_id' => \auth()->user()->id])->first();
            if (!empty($company_item)) {
                return redirect()->back()->withInput()->withErrors('Товар с таким кодом уже существует');
            }
        }
        $validator = $this->validator($request);
        $validator['validator']->validate();

        if (items::action(null, $validator['inputs'])) {
            Notify::success('Товар успешно добавлен.');

            return redirect()->route('company.items.list');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit_put(Request $request, $id)
    {
        $item = Items::where('code', lower_case($request->code))->orWhere('code', $request->code)->orWhere('code', strtoupper($request->code))->first();
        if (!empty($item) && $item->id != $id) {

            $item_id = $item->id;
            $company_item = CompanyItems::where(['item_id' => $item_id, 'user_id' => \auth()->user()->id])->first();
            if (!empty($company_item)) {
                return redirect()->back()->withInput()->withErrors('Товар с таким кодом уже существует');
            }
        }

        $user = User::where('id', $request->company)->where('type', 1)->firstOrfail();
        $validator = $this->validator($request, true);
        $validator['validator']->validate();
        $model = Items::where('id', $id)->firstOrFail();
        if (items::action($model, $validator['inputs'])) {
            Notify::success('Товар успешно добавлен.');

            return redirect()->route('company.items.list');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function delete(Request $request)
    {
        $model = Items::where('id', $request->item_id)->firstOrFail();

        Items::deleteItem($model);

        return \redirect()->route('company.items.list');
    }

    private function validator($request, $ignore = false)
    {
        $inputs = $request->all();
        if (!empty($inputs['url'])) $inputs['url'] = lower_case($inputs['url']);
        $inputs['generated_url'] = to_url($inputs['title']['ru']);
        $request->merge(['url' => $inputs['url']]);

        $unique = $ignore === false ? null : ',' . $ignore;
        $result = [];
        $rules = [

            'categories' => 'int|required',
            'criterion' => 'array',
            'code' => 'required|string|max:255',
            'count' => 'required|int|max:255',
            'price' => 'required|int|max:1000000000',
            'delivery_price' => 'int|max:100|min:1|nullable',

        ];
        if (empty($inputs['generate_url'])) {
            $rules['url'] = 'required|is_url|string|unique:items,url' . $unique . '|nullable';
        }
        if (!$ignore) {
            $rules['image'] = 'required|image';
        } else {
            $rules['image'] = 'image|nullable';
        }
        $result['validator'] = \Illuminate\Support\Facades\Validator::make($inputs, $rules, [
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
            'price.required' => ' Ввведите Цену товара',
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }


    public function export()
    {
        $categories = Category::where('deep', 0)->with('childrens')->get();

        // return Excel::download(new CategoryAndFilterExport(), 'CategoryAndFilterExport.xlsx');
    }

}
