<?php

namespace App\Http\Controllers\Admin;

use App\Models\Packages;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagesController extends BaseController
{
    public function main()
    {

        $data = ['title' => 'Пакеты'];

        $data['items'] = Packages::adminList();

        return view('admin.pages.packages.main', $data);
    }

    public function add()
    {
        return redirect()->back();
        $data = [];
        $data['title'] = 'Добавление Пакеты';
        $data['back_url'] = route('admin.packages.main');
        $data['edit'] = false;

        return view('admin.pages.packages.form', $data);
    }

    public function add_put(Request $request)
    {
        return redirect()->back();

        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (packages::action(null, $validator['inputs'])) {
            Notify::success('Пакет успешно добавлен.');

            return redirect()->route('admin.packages.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = Packages::where('id', $id)->firstOrFail();
        $data['title'] = 'Редактирование Пакеты';
        $data['back_url'] = route('admin.packages.main');
        $data['edit'] = true;

        return view('admin.pages.packages.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = Packages::where('id', $id)->firstOrFail();
        $validator = $this->validator($request, $id);
        $validator['validator']->validate();
        if (packages::action($item, $validator['inputs'])) {
            Notify::success('Пакет успешно редактирован.');

            return redirect()->route('admin.packages.edit', ['id' => $item->id]);
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
            $item = packages::where('id', $id)->first();
            if ($item && packages::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($request, $ignore = false)
    {
        $inputs = $request->all();

        $unique = $ignore === false ? null : ',' . $ignore;
        $result = [];
        $rules = [
            'title.*' => 'required|string|max:255',
            'count_products' => 'required|int|min:0',
            'package_price' => 'required|int|min:0',
            'count_images' => 'required|int|min:0',


        ];

        $result['validator'] = Validator::make($inputs, $rules, [
            'package_price.required' => 'Введите цену для пакета',
            'title.required' => 'Введите название',
            'title.string' => 'Введите название',
            'title.max' => 'Введите название (мах:255см)',

            'count_products.required' => ' Введите кол-во товаров',
            'count_products.int' => ' кол-во товаров (0 , 1 , ... , 9999)',
            'count_products.min' => 'мин кол-во товаров 0',
            'count_images.required' => 'Введите кол-во изображений',
            'count_images.int' => ' кол-во изображений (0 , 1 , ... , 9999)',
            'count_images.min' => 'мин кол-во изображений 0',
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }
}
