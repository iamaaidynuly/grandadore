<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductOption;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductOptionsController extends BaseController
{
    public function main()
    {
        $data = ['title' => 'Добавки продуктов'];
        $data['items'] = ProductOption::adminList();

        return view('admin.pages.product_options.main', $data);
    }

    public function add()
    {
        $data = [];
        $data['title'] = 'Добавление добавки продуктов';
        $data['back_url'] = route('admin.product_options.main');
        $data['edit'] = false;

        return view('admin.pages.product_options.form', $data);
    }

    public function add_put(Request $request)
    {
        $inputs = $request->all();
        $this->validator($inputs)->validate();
        if (ProductOption::action(null, $inputs)) {
            Notify::success('Добавка продуктов успешно добавлена.');

            return redirect()->route('admin.product_options.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = ProductOption::getItem($id);
        $data['title'] = 'Редактирование добавки продуктов';
        $data['back_url'] = route('admin.product_options.main');
        $data['edit'] = true;

        return view('admin.pages.product_options.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = ProductOption::getItem($id);
        $inputs = $request->all();
        $this->validator($inputs, true)->validate();
        if (ProductOption::action($item, $inputs)) {
            Notify::success('Добавка продуктов успешно редактирована.');

            return redirect()->route('admin.product_options.edit', ['id' => $item->id]);
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
            $item = ProductOption::where('id', $id)->first();
            if ($item && ProductOption::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    public function sort()
    {
        return ProductOption::sortable();
    }


    private function validator($inputs, $edit = false)
    {

        $rules = [
            'image' => $edit ? 'nullable' : 'required' . '|image',
        ];

        return Validator::make($inputs, $rules, [
            'image.required' => 'Выберите Изображение',
            'image.image' => 'Неверное Изображение',
        ]);
    }

}
