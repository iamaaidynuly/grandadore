<?php

namespace App\Http\Controllers\Site;

use App\Mail\OrderSent;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Filter;
use App\Models\Items;
use App\Models\Order;
use App\Models\Product;
use App\Models\Support;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProductsController extends BaseController
{
    public function show($url)
    {
        if (!is_active('menu')) abort(404);
        $data = [];
        $data['item'] = Product::getItemSite($url);
        $data['seo'] = $this->renderSEO($data['item']);
        $data['other_products'] = Product::getRandom($data['item']->id);
        $data['banners'] = Banner::get('menu');

        return view('site.pages.product', $data);
    }

    public function basket()
    {
        $data = [];
        $data['basket_hidden'] = true;
        $data['noindex'] = true;
//        session(['referer'=>url()->previous()]);
        $data['seo'] = $this->staticSEO(__('app.basket'));

        $data['delivery_prices'] = Delivery::siteList();

        if ($data['delivery_prices_count'] = count($data['delivery_prices'])) {
            $data['deliveryChecked'] = oldCheck('delivery', false);
            $data['oldDistrict'] = old('district');
            $data['oldPrice'] = 0;
            if ($data['oldDistrict'] && ($thisDistrict = $data['delivery_prices']->where('id', $data['oldDistrict'])->first())) {
                $data['oldPrice'] = $thisDistrict->price;
            } else {
                $thisDistrict = $data['delivery_prices']->first();
                $data['oldDistrict'] = $thisDistrict->id;
                $data['oldPrice'] = $thisDistrict->price;
            }
        }

        return view('site.pages.basket', $data);
    }

    public function changeRating(Request $request)
    {
        $this->validate($request, [
            'itemId' => 'required|exists:items,id',
            'rating' => 'required|numeric|min:0|max:5'
        ]);

        if (!auth()->check()) {
            return response()->make('Unprocessable entity')->setStatusCode(419);
        }
        /** @var Items $item */
        $item = Items::with('rates')->where('id', $request->input('itemId'))->first();

        $item->rates()->updateOrCreate([
            'user_id' => auth()->id()
        ], [
            'rating' => $request->input('rating')
        ]);

        return response()->make('Rating changed')->setStatusCode(200);
    }

    public function getPortion(Request $request)
    {
        $items = Items::with(['rates', 'sizes','criteria'])
            ->where('count', '>', 0)
            ->where(['active' => 1, 'moderated' => 1]);

        $category = Category::with('children')->where('url', $request->query('category'))->first();
        $items = $this->filterByRequest($items, $request);
        $items = $items->paginate(20);
        $items->withPath(route('products.category.list', ['url' => $category->url]));

        return response()->view('site.layouts.products-constructor', [
            'items' => $items ,
        ]);
    }

    public function getPriceRange(Request $request)
    {
        $items = Items::query()
            ->select('id', 'price', 'delivery_price')
            ->where('count', '>', 0)
            ->where(['active' => true, 'moderated' => true]);

        $items = $this->filterByRequest($items, $request, false);

        return response()->json([
            'min' => $items->min('price'),
            'max' => $items->max('price'),
        ]);
    }

    public function search(Request $request)
    {
        if (empty($request->query('searchQuery'))) {
            return redirect()->back()->withErrors(['search_empty' => 'Заполните поле']);
        }
        $query_parts = explode(' ', $request->query('searchQuery'));

        $items = Items::query();
        $items = $items->where(function ($q) use ($query_parts) {
            foreach ($query_parts as $part) {
                $q->where('code', 'LIKE', '%' . $part . '%')
                    ->orWhere('title->ru', 'LIKE', '%' . $part . '%')
                    ->orWhere('title->en', 'LIKE', '%' . $part . '%')
                    ->orWhere('title->kz', 'LIKE', '%' . $part . '%')
                    ->orWhere('description->ru', 'LIKE', '%' . $part . '%')
                    ->orWhere('description->en', 'LIKE', '%' . $part . '%')
                    ->orWhere('description->kz', 'LIKE', '%' . $part . '%')
                    ->orWhere('short->ru', 'LIKE', '%' . $part . '%')
                    ->orWhere('short->en', 'LIKE', '%' . $part . '%')
                    ->orWhere('short->kz', 'LIKE', '%' . $part . '%');
            }
        });

        $data['items'] = $items->has('categories')->orderByDesc('in_stock')->paginate(36);
        $data['items']->appends($request->query());
        $data['search'] = $request->query('searchQuery');
        $data['seo'] = $this->staticSEO($request->query('searchQuery') . ' - ' . __('app.search results'));

        return view('site.pages.products.search', $data);
    }

    /**
     * @param $items
     * @param Request $request
     * @param bool $filterByPrice
     * @return Builder
     */
    protected function filterByRequest(Builder $items, Request $request, $filterByPrice = true)
    {
        if ($categoryUrl = $request->query('category')) {
            $category = Category::with('children')->where('url', $categoryUrl)->firstOrFail();

            $children = $category->children;
            $categoryIds = [];

            if (count($children)) {
                foreach($children as $child) {
                    $categoryIds[] = $child->id;

                    if (count($child->children)) {
                        foreach($child->children as $grandchild) {
                            $categoryIds[] = $grandchild->id;
                        }
                    }
                }
            }

            $categoryIds[] = $category->id;

            $items = $items->whereHas('categories', function (Builder $query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            });
        }
        if ($filterByPrice && ($colorFilters = $request->query('colorFilters'))) {
            $colorFilters = json_decode($colorFilters);
            $items = $items->whereHas('colorFilters', function (Builder $query) use ($colorFilters) {
                $query->whereIn('color_filters.id', $colorFilters);
            });
        }

        if ($filterByPrice && ($criteriaIds = $request->query('criteria'))) {
            $criteriaIds = json_decode($criteriaIds);
            $filters = Filter::with(['criteria' => function (HasMany $criteria) use ($criteriaIds) {
                $criteria->whereIn('criteria.id', $criteriaIds);
            }])->whereHas('criteria', function (Builder $query) use ($criteriaIds) {
                return $query->whereIn('criteria.id', $criteriaIds);
            })->get();

            foreach ($filters as $filter) {
                $items = $items->whereHas('criteria', function (Builder $query) use ($filter) {
                    $ids = $filter->criteria->pluck('id')->toArray();
                    $query->whereIn('criteria.id', $ids);
                });
            }
        }

        if ($filterByPrice && ($priceRange = $request->query('priceRange'))) {
            $priceRange = json_decode($priceRange, true);

            $items = $items->whereBetween('price', [$priceRange['from'], $priceRange['to']]);
        }

        $items->orderByDesc('in_stock');
        if ($filterByPrice && ($sortType = $request->query('sortType'))) {
            switch ($sortType) {
                case 1:
                    $column = 'created_at';
                    $direction = 'desc';
                    break;
                case 2:
                    $column = 'created_at';
                    $direction = 'asc';
                    break;
                case 3:
                    $column = 'price';
                    $direction = 'desc';
                    break;
                case 4:
                    $column = 'price';
                    $direction = 'asc';
                    break;
                default:
                    $column = 'created_at';
                    $direction = 'desc';
            }

            $items = $items->orderBy($column, $direction);
        }

        return $items;
    }

    public function faq(){
        $supportAll=Support::where('id','>','0')->get();
        return view('site.faq',compact('supportAll'));
    }

    public function ready(){
        return view('site.pages.cabinet.ready');

    }
}
