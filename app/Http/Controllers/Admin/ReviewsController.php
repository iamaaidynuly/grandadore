<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReviewItem;
use App\Models\Reviews;
use Illuminate\Http\Request;

class ReviewsController extends BaseController
{

    public function main($id = null)
    {
        $data = ['title' => 'Отзывы'];
        $data['items'] = Reviews::adminList();
        if (!empty($id)) {
            $ids = ReviewItem::where('item_id', $id)->pluck('review_id');
            $data['items'] = Reviews::whereIn('id', $ids)->get();
        }


        return view('admin.pages.reviews.main', $data);
    }


    public function view($id)
    {
        $data = [];
        $data['item'] = Reviews::getItem($id);
        $data['title'] = 'Просмотр Отзыва';
        $data['back_url'] = route('admin.reviews.main');
        $data['edit'] = true;

        return view('admin.pages.reviews.form', $data);
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        $item = Reviews::where('id', $id)->first();
        if ($item && Reviews::deleteItem($item)) {
            ReviewItem::where('review_id', $id)->delete();
            $result['success'] = true;
        }

        return response()->json($result);
    }

    public function ModerateItem($id)
    {
        $item = Reviews::where('id', $id)->firstOrFail();
        if ($item->moderated) {
            $item->moderated = 0;
        } else {
            $item->moderated = 1;
        }
        $item->save();

        return \redirect()->back();
    }

}
