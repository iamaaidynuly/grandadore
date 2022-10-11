<?php

namespace App\Http\Controllers\Site;

use App\Models\Category;
use App\Models\ItemCriterions;
use Illuminate\Http\Request;

class FilterCheckingController extends BaseController
{
    public function checking(Request $request)
    {
        $orderBY = false;
        $price_type = $request->price_type;

        if (!empty($request->price_type) && $request->price_type) {
            if ((int)$request->price_type == 1) {
                $orderBY = 'DESC';
            } else {
                $orderBY = 'ASC';
            }
        }

        $checkeds = $request->checked;
        $category_id = $request->category_id;
        $price = $request->price;
        if (empty($checkeds)) {
            $category = Category::where('id', $category_id)->with(['childrens' => function ($q) use ($price, $orderBY) {
                return $q->with(['items' => function ($q) use ($price, $orderBY) {
                    $q = $q->where('price', '>=', (int)$price['min'])->where('price', '<=', (int)$price['max']);
                    if ($orderBY) {
                        $q->orderBy('price', $orderBY);
                    }
                }])->get();
            }])->with(['items' => function ($q) use ($price, $orderBY) {
                $q = $q->where('price', '>=', (int)$price['min'])->where('price', '<=', (int)$price['max']);
                if ($orderBY) {
                    $q->orderBy('price', $orderBY);
                }
            }])->firstOrFail();

            return response()->view('site.components.itemList', compact('category', 'price_type'));
        } else {
            $items_ids = ItemCriterions::whereIn('criteria_id', $checkeds)->pluck('item_id')->toArray();
            $category = Category::where('id', $category_id)->with(['childrens' => function ($q) use ($items_ids, $price, $orderBY) {
                return $q->with(['items' => function ($q) use ($items_ids, $price, $orderBY) {
                    $q = $q->whereIn('items.id', $items_ids)->where('price', '>=', (int)$price['min'])->where('price', '<=', (int)$price['max']);
                    if ($orderBY) {
                        $q->orderBy('price', $orderBY);
                    }
                }])->get();
            }])->with(['items' => function ($q) use ($items_ids, $price, $orderBY) {
                $q = $q->whereIn('items.id', $items_ids)->where('price', '>=', (int)$price['min'])->where('price', '<=', (int)$price['max']);
                if ($orderBY) {
                    $q->orderBy('price', $orderBY);
                }
            }])->firstOrFail();

            return response()->view('site.components.itemList', compact('category', 'price_type'));
        }

    }
}
