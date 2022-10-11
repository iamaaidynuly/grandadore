<?php

namespace App\Http\Controllers\Site;

use App\Models\Gallery;
use App\Models\News;
use App\Models\Page;

class NewsController extends BaseController
{
    public function news_item($url)
    {
        $data = [];
        $data['current_page'] = Page::where('static', 'news')->first();;
        $data['item'] = News::getItemSite($url);
        $data['item']->views_count++;
        $data['item']->save();
        $data['seo'] = $this->renderSEO($data['item']);
        $data['gallery'] = Gallery::get('news_item', $data['item']->id);

        $breadcrumbs = [
            [
                'title' => $data['current_page']->title,
                'url' => route('page', ['url'=>$data['current_page']->url])
            ],
            [
                'title' => $data['item']->title,
                'url' => ''
            ]
        ];

        $data['breadcrumbs'] = $breadcrumbs;

        return view('site.pages.news.view', $data);
    }
}
