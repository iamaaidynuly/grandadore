<?php

namespace App\Http\Controllers\Site;

use App\Models\Gallery;
use App\Models\ItemCategories;
use App\Models\Page;
use App\Models\Rooms;

class RoomsController extends BaseController
{
    public function room($url)
    {

        $id = Page::where('static', 'rooms')->first()->id;
        $data['current_page'] = $id;
        $data['room'] = Rooms::where('url', $url)->firstOrFail();

        $data['types'] = ItemCategories::whereIn('id', json_decode($data['room']->types_ids))->get();
        $data['gallery'] = Gallery::get('rooms_item', $data['room']->id);
        $data['seo'] = $this->renderSEO($data['room']);

        return view('site.pages.rooms.view', $data);
    }
}
