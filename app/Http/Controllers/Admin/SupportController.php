<?php

namespace App\Http\Controllers\Admin;
//use App\Custom\Notify\Facades\Notify;
use App\Models\Support;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends BaseController
{
    public function main()
    {
        $data = ['title' => 'Вопросы'];
        $data['items'] = Support::all();

        return view('admin.pages.support.main', $data);
    }

    public function add()
    {
        $data = [];
        $data['title'] = 'Добавление вопроса';
        $data['back_url'] = route('admin.support.main');
        $data['edit'] = false;

        return view('admin.pages.support.form', $data);

    }

    public function add_put(Request $request)
    {

        $inputs = $request->all();

        $this->validator($inputs)->validate();
        if (Support::action(null, $inputs)) {
            Notify::success('Вопрос успешно добавлен.');

            return redirect()->route('admin.support.add');
        } else {
            Notify::get('error_occured');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = Support::where('id', $id)->firstOrFail();
        $data['title'] = 'Редактирование вопроса';
        $data['back_url'] = route('admin.support.main');
        $data['edit'] = true;

        return view('admin.pages.support.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = Support::where('id', $id)->firstOrFail();
        $inputs = $request->all();
        $this->validator($inputs, true)->validate();
        if (Support::action($item, $inputs)) {
            Notify::success('Вопрос успешно редактирован.');

            return redirect()->route('admin.support.edit', ['id' => $item->id]);
        } else {
            Notify::get('error_occured');

            return redirect()->back()->withInput();
        }
    }

    public function delete(Request $request)
    {
        $result = ['success' => false];
        $id = $request->input('item_id');
        if ($id && is_id($id)) {
            $page = Support::where('id', $id)->first();
            if ($page && Support::deleteItem($page)) $result['success'] = true;
        }

        return response()->json($result);
    }

    public function sort()
    {
        return Support::sortable();
    }


    private function validator($inputs, $edit = false)
    {
        $result = [];
        $rules = !$edit ? [
            'title*' => 'required|string|max:255',
            'answer*' => 'required|string|max:255',
        ] : [

            'title*' => 'string|required|max:255',
            'answer*' => 'required|string|max:255',
        ];

        return Validator::make($inputs, $rules, [
            'title.string' => 'Введите вопрос.',
            'title.required' => 'Введите вопрос',
            'title.max' => 'Длина вопроса мах:255',
            'answer.string' => 'Введите ответ.',
            'answer.required' => 'Введите ответ',
            'answer.max' => 'Длина ответа мах:255'
        ]);
    }
}
