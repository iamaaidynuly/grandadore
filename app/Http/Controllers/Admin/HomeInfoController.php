<?php

namespace App\Http\Controllers\Admin;

use App\Models\HomeInfo;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeInfoController extends BaseController
{
    public function main()
    {
        $data = ['title' => 'Преимущества'];
        $data['items'] = HomeInfo::adminList();

        return view('admin.pages.home.main', $data);
    }

    public function add()
    {
        $data = [];
        $data['title'] = 'Добавление Преимущества';
        $data['back_url'] = route('admin.home.main');
        $data['edit'] = false;

        return view('admin.pages.home.form', $data);
    }

    public function add_put(Request $request)
    {
        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (HomeInfo::action(null, $validator['inputs'])) {
            Notify::success('Преимущества успешно добавлен.');

            return redirect()->route('admin.home.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = HomeInfo::getItem($id);
        $data['title'] = 'Редактирование Преимущества';
        $data['back_url'] = route('admin.home.main');
        $data['edit'] = true;

        return view('admin.pages.home.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = HomeInfo::getItem($id);
        $validator = $this->validator($request, $id);
        $validator['validator']->validate();
        if (HomeInfo::action($item, $validator['inputs'])) {
            Notify::success('Преимущества успешно редактирован.');

            return redirect()->route('admin.home.edit', ['id' => $item->id]);
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
            $item = HomeInfo::where('id', $id)->first();
            if ($item && HomeInfo::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($request, $ignore = false)
    {
        $inputs = $request->all();
        $unique = $ignore === false ? null : ',' . $ignore;
        $result = [];
        $rules = [
            'title.*' => 'required'
        ];

        if (!$ignore) {
            $rules['image'] = 'required|image';
        } else {
            $rules['image'] = 'image|nullable';
        }
        $result['validator'] = Validator::make($inputs, $rules, [
            'image.image' => 'Неверное Изображение.',
            'image.required' => 'Выберите Изображение.',
            'title.*.required' => 'Введите название.',
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }
}
