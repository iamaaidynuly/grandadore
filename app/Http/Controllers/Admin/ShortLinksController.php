<?php

namespace App\Http\Controllers\Admin;

use App\Models\short_linksInfo;
use App\Models\ShortLinks;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShortLinksController extends BaseController
{
    public function main()
    {
        $data = ['title' => 'Короткие ссылки'];
        $data['items'] = ShortLinks::adminList();

        return view('admin.pages.short_links.main', $data);
    }

    public function add()
    {
        $data = [];
        $data['title'] = 'Добавление Коротких ссылок';
        $data['back_url'] = route('admin.short_links.main');
        $data['edit'] = false;

        return view('admin.pages.short_links.form', $data);
    }

    public function add_put(Request $request)
    {
        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (ShortLinks::action(null, $validator['inputs'])) {
            Notify::success('Короткая ссылка успешно добавлена.');

            return redirect()->route('admin.short_links.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        
        $data = [];
        $data['item'] = ShortLinks::getItem($id);
        $data['title'] = 'Редактирование Коротких ссылок';
        $data['back_url'] = route('admin.short_links.main');
        $data['edit'] = true;

        return view('admin.pages.short_links.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = ShortLinks::getItem($id);
        $validator = $this->validator($request, $id);
        $validator['validator']->validate();
        if (ShortLinks::action($item, $validator['inputs'])) {
            Notify::success('Короткая ссылка успешно редактирована.');

            return redirect()->route('admin.short_links.edit', ['id' => $item->id]);
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
            $item = ShortLinks::where('id', $id)->first();
            if ($item && ShortLinks::deleteItem($item)) $result['success'] = true;
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
