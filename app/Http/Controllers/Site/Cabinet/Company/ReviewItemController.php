<?php

namespace App\Http\Controllers\Site\Cabinet\Company;

use App\Http\Controllers\Site\BaseController;
use App\Models\ReviewItem;
use App\Models\Reviews;

class   ReviewItemController extends BaseController
{


    public function main($id = null)
    {
        $data = ['title' => 'Отзывы'];
        $data['items'] = Reviews::adminList();
        if (!empty($id)) {
            $ids = ReviewItem::where('item_id', $id)->pluck('review_id');
            $data['items'] = Reviews::whereIn('id', $ids)->get();
        }
        $data['role'] = true;

        return view('site.pages.cabinet.company.items.reviews', $data);
    }


    public function view($id)
    {

        $data = [];
        $data['role'] = true;

        $data['item'] = Reviews::getItem($id);
        $data['title'] = 'Просмотр Отзыва';
        $data['back_url'] = route('admin.reviews.main');
        $data['edit'] = true;

        return view('site.pages.cabinet.company.items.review', $data);
    }


}
