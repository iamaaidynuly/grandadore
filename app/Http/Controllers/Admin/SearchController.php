<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Search;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends BaseController
{
    public function search(){

        $search = Search::where('id','>',0)->get();

        return view('admin.pages.search.search',compact('search'));
    }

    public function addSearch(){
        return view('admin.pages.search.addSearch');
    }

    public function createSearch(Request $request){
        if($request['title']['ru'] || $request['title']['kz']){
            if (Search::action(null, $request)) {
                Notify::success('Популярные поиски успешно добавлен.');

                return redirect()->back();
            } else {
                Notify::get('error_occurred');

                return redirect()->back()->withInput();
            }
        }else {
            return back()->with('error', 'Failed to find that resource');
        }
    }

    public function editSearch($id){
        $search=Search::find($id);
        return view('admin.pages.search.editSearch',compact('search'));
    }

    public function editThisSearch($id , Request $request){
        $item = Search::getItem($id);

        if (Search::action($item, $request)) {
            Notify::success('Популярные поиски успешно редактирован.');

            return redirect()->route('admin.editSearch', ['id' => $item->id]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }


    public function changeStatus(Request $request ){
        $search=Search::find($request->id);

        $search->active = !$search->active;
        $search->update();

    }

    public function deleteSearch(Request $request){
        Search::find($request->id)->delete();
        return back() ;
    }


}
