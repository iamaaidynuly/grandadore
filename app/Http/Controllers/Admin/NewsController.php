<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends BaseController
{
    public function main()
    {
        $data = ['title' => 'Новости'];
        $data['items'] = News::adminList();
        $data['back_url'] = route('admin.pages.main');

        return view('admin.pages.news.main', $data);
    }

    public function add()
    {
        $data = [];
        $data['title'] = 'Добавление новости';
        $data['back_url'] = route('admin.news.main');
        $data['edit'] = false;

        return view('admin.pages.news.form', $data);
    }

    public function add_put(Request $request)
    {
        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (News::action(null, $validator['inputs'])) {
            Notify::success('Новость успешно добавлен.');

            return redirect()->route('admin.news.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = News::getItem($id);
        $data['title'] = 'Редактирование новости';
        $data['back_url'] = route('admin.news.main');
        $data['edit'] = true;

        return view('admin.pages.news.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = News::getItem($id);
        $validator = $this->validator($request, $id);
        $validator['validator']->validate();
        if (News::action($item, $validator['inputs'])) {
            Notify::success('Новость успешно редактирован.');

            return redirect()->route('admin.news.edit', ['id' => $item->id]);
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
            $item = News::where('id', $id)->first();
            if ($item && News::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($request, $ignore = false)
    {
        $inputs = $request->all();
        if (!empty($inputs['url'])) $inputs['url'] = lower_case($inputs['url']);
        $inputs['generated_url'] = !empty($inputs['title'][$this->urlLang]) ? to_url($inputs['title'][$this->urlLang]) : null;
        $request->merge(['url' => $inputs['url']]);
        $unique = $ignore === false ? null : ',' . $ignore;
        $result = [];
        $rules = [
            'generated_url' => 'required_with:generate_url|string|nullable',
        ];
        if (empty($inputs['generate_url'])) {
            $rules['url'] = 'required|is_url|string|unique:news,url' . $unique . '|nullable';
        }
        if (!$ignore) {
            $rules['image'] = 'required|image';
        } else {
            $rules['image'] = 'image|nullable';
        }
        $result['validator'] = Validator::make($inputs, $rules, [
            'generated_url.required_with' => 'Введите название (' . $this->urlLang . ') чтобы сгенерировать URL.',
            'url.required' => 'Введите URL или подставьте галочку "сгенерировать автоматический".',
            'url.is_url' => 'Неправильный URL.',
            'url.unique' => 'URL уже используется.',
            'image.image' => 'Неверное Изображение.',
            'image.required' => 'Выберите Изображение.',
            'image_cover.required' => 'Выберите Изображение заголовка'
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }
}
