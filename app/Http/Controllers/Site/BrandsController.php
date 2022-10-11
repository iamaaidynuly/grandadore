<?php

namespace App\Http\Controllers\Site;

use App\Models\Gallery;
use App\Models\Page;
use App\Models\Brands;
use App\Models\BrandsItems;

class BrandsController extends BaseController
{
    public function brand_view($url)
    {
        $data['current_page'] = Page::where('static', 'brands')->first();
        $data['brand'] = Brands::getItemSite($url);
        $data['item_brands'] = BrandsItems::where('brand_id', $data['brand']->id)->with('items')->take(20)->get();

        $data['gallery'] = Gallery::get('brands_item', $data['brand']->id);
        $data['seo'] = $this->renderSEO($data['brand']);

        $breadcrumbs = [
            [
                'title' => $data['current_page']->title,
                'url' => route('page', ['url'=>$data['current_page']->url])
            ],
            [
                'title' => $data['brand']->title,
                'url' => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return view('site.pages.brands.view', $data);
    }
}
