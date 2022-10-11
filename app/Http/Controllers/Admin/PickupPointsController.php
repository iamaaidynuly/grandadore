<?php

namespace App\Http\Controllers\Admin;

use App\Models\PickupPoint;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PickupPointsController extends BaseController
{
    public function main()
    {
        $data = [];
        $data['title'] = 'Точки самовывоза';
        $data['items'] = PickupPoint::adminList();

        return view('admin.pages.pickup_points.main', $data);
    }

    public function add()
    {
        $data = ['edit' => false];
        $data['title'] = 'Добовление точки самовывоза';
        $data['back_url'] = route('admin.pickup_points.main');

        return view('admin.pages.pickup_points.form', $data);
    }

    public function add_put(Request $request)
    {
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        if (PickupPoint::action(null, $inputs)) {
            Notify::success('Точка самовывоза добавлена.');

            return redirect()->route('admin.pickup_points.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = ['edit' => true];
        $data['item'] = PickupPoint::getItem($id);
        $data['title'] = 'Редактирование точки самовывоза';
        $data['back_url'] = route('admin.pickup_points.main');

        return view('admin.pages.pickup_points.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = PickupPoint::getItem($id);
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        if (PickupPoint::action($item, $inputs)) {
            Notify::success('Точка самовывоза редактирована.');

            return redirect()->route('admin.pickup_points.edit', ['id' => $item->id]);
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function sort()
    {
        return PickupPoint::sortable();
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        if ($id && is_id($id)) {
            $item = PickupPoint::where('id', $id)->first();
            if ($item && PickupPoint::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($inputs)
    {
        return Validator::make($inputs, [
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'lat' => 'required|string|max:25|regex:/^-?[0-9]+(\.[0-9]+)?$/',
            'lng' => 'required|string|max:25|regex:/^-?[0-9]+(\.[0-9]+)?$/',
        ]);
    }
}
