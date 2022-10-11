<?php

namespace App\Http\Controllers\Admin;

use App\Models\MinimumTotalCost;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MinimumTotalCostController extends BaseController
{
//    public function main()
//    {
//        $data = ['title' => 'Регионы доставки'];
//        $data['items'] = MinimumTotalCost::adminList();
//
//        return view('admin.pages.delivery_regions.main', $data);
//    }

//    public function add()
//    {
//        $data = ['title' => 'Добавление региона доставки', 'edit' => false];
//        $data['back_url'] = route('admin.delivery_regions.main');
//
//        return view('admin.pages.delivery_regions.form', $data);
//    }

//    public function add_put(Request $request)
//    {
//        $inputs = $request->all();
//        $this->validator($inputs, false)->validate();
//        if (DeliveryRegion::action(null, $inputs)) {
//            Notify::success('Регион добавлен.');
//
//            return redirect()->route('admin.delivery_regions.main');
//        } else {
//            Notify::get('error_occurred');
//
//            return redirect()->back()->withInput();
//        }
//    }

    public function edit($id)
    {

        $data = ['title' => 'Редактирование минимальная общая стоимость', 'edit' => true];
        $data['back_url'] = route('admin.delivery_regions.main');
        $data['item'] = MinimumTotalCost::getItem($id);


        return view('admin.pages.minimum_total_cost.edit', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = MinimumTotalCost::getItem($id);
        $inputs = $request->all();
//        dd($inputs);
        $this->validator($inputs, $item->id)->validate();

        if (MinimumTotalCost::action($item, $inputs)) {
            Notify::success('Регион редактирован.');

            return redirect()->route('admin.delivery_regions.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

//    public function delete(Request $request)
//    {
//        $result = ['success' => false];
//        $id = $request->input('item_id');
//        if ($id && is_id($id)) {
//            $item = MinimumTotalCost::where('id', $id)->first();
//            if ($item && MinimumTotalCost::deleteItem($item)) $result['success'] = true;
//        }
//
//        return response()->json($result);
//    }

    private function validator($inputs, $ignore = false)
    {

        return Validator::make($inputs, [
            'price' => 'required|int',
            'text.*' => 'required',
        ]);
    }
}
