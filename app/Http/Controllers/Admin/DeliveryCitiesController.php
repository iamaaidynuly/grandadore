<?php

namespace App\Http\Controllers\Admin;

use App\Models\DeliveryCity;
use App\Models\DeliveryRegion;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryCitiesController extends BaseController
{
    public function main($id)
    {
        $data = [];
        $data['region'] = DeliveryRegion::getItem($id);
        $data['items'] = $data['region']->cities;
        $data['title'] = 'Населенные пункты региона "' . $data['region']->title . '"';
        $data['back_url'] = route('admin.delivery_regions.main');

        return view('admin.pages.delivery_cities.main', $data);
    }

    public function add($id)
    {
        $data = ['edit' => false];
        $data['region'] = DeliveryRegion::getItem($id);
        $data['title'] = 'Добавление населенного пункта "' . $data['region']->title . '"';
        $data['back_url'] = route('admin.delivery_regions.main', ['id' => $data['region']->id]);

        return view('admin.pages.delivery_cities.form', $data);
    }

    public function add_put($id, Request $request)
    {
        $region = DeliveryRegion::getItem($id);
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        $inputs['region_id'] = $region->id;
        if (DeliveryCity::action(null, $inputs)) {
            Notify::success('Населенный пункт добавлен.');

            return redirect()->route('admin.delivery_cities.main', ['id' => $region->id]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = ['edit' => true];
        $data['item'] = DeliveryCity::getItem($id);
        $data['region'] = $data['item']->region;
        $data['title'] = 'Редактирование населенного пункта';
        $data['back_url'] = route('admin.delivery_cities.main', ['id' => $data['region']->id]);

        return view('admin.pages.delivery_cities.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = DeliveryCity::getItem($id);
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        if (DeliveryCity::action($item, $inputs)) {
            Notify::success('Населенный пункт редактирован.');

            return redirect()->route('admin.delivery_cities.edit', ['id' => $item->id]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        if ($id && is_id($id)) {
            $item = DeliveryCity::where('id', $id)->first();
            if ($item && DeliveryCity::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    public function validator($inputs)
    {
        return Validator::make($inputs, [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|between:1,1000000000',
            'min_price' => 'nullable|numeric|between:1,1000000000',
        ]);
    }
}
