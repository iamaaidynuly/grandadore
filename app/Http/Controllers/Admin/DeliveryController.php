<?php

namespace App\Http\Controllers\Admin;

use App\Models\Delivery;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends BaseController
{
    public function main()
    {
        $data = ['title' => 'Цены доставки'];
        $data['items'] = Delivery::adminList();

        return view('admin.pages.delivery.main', $data);
    }

    public function add()
    {
        $data = [];
        $data['title'] = 'Добавление района';
        $data['back_url'] = route('admin.delivery.main');
        $data['edit'] = false;

        return view('admin.pages.delivery.form', $data);
    }

    public function add_put(Request $request)
    {
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        if (Delivery::action(null, $inputs)) {
            Notify::success('Район успешно добавлен.');

            return redirect()->route('admin.delivery.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = Delivery::getItem($id);
        $data['title'] = 'Редактирование района';
        $data['back_url'] = route('admin.delivery.main');
        $data['edit'] = true;

        return view('admin.pages.delivery.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = Delivery::getItem($id);
        $inputs = $request->all();
        $this->validator($inputs, true)->validate();
        if (Delivery::action($item, $inputs)) {
            Notify::success('Район успешно редактирована.');

            return redirect()->route('admin.delivery.edit', ['id' => $item->id]);
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
            $item = Delivery::where('id', $id)->first();
            if ($item && Delivery::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    public function sort()
    {
        return Delivery::sortable();
    }


    private function validator($inputs, $edit = false)
    {
        $rules = [
            'price' => 'required|numeric|between:1,1000000',
        ];

        return Validator::make($inputs, $rules, [
            'price.required' => 'Введите цену доставки',
            'price.numeric' => 'Цена доставки должна иметь только цифры',
            'price.between' => 'Цена доставки должна быть между :min и :max',
        ]);
    }
}
