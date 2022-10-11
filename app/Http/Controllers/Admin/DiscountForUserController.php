<?php

namespace App\Http\Controllers\Admin;

use App\Models\DiscountForUser;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountForUserController extends BaseController
{
    public function main()
    {
        $data = ['title' => 'Скидки для пользователей'];
        $data['items'] = DiscountForUser::adminList();

        return view('admin.pages.discountForUser.main', $data);
    }

    public function add()
    {
        $data = [];
        $data['title'] = 'Добавление скидки';
        $data['back_url'] = route('admin.discountForUser.main');
        $data['edit'] = false;

        return view('admin.pages.discountForUser.form', $data);
    }

    public function add_put(Request $request)
    {
        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (discountForUser::action(null, $validator['inputs'])) {
            Notify::success('Новость успешно добавлен.');

            return redirect()->route('admin.discountForUser.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = discountForUser::getItem($id);
        $data['title'] = 'Редактирование новости';
        $data['back_url'] = route('admin.discountForUser.main');
        $data['edit'] = true;

        return view('admin.pages.discountForUser.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = discountForUser::getItem($id);
        $validator = $this->validator($request, $id);
        $validator['validator']->validate();
        if (discountForUser::action($item, $validator['inputs'])) {
            Notify::success('Новость успешно редактирован.');

            return redirect()->route('admin.discountForUser.edit', ['id' => $item->id]);
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
            $item = discountForUser::where('id', $id)->first();
            if ($item && discountForUser::deleteItem($item)) $result['success'] = true;
        }

        return response()->json($result);
    }

    private function validator($request, $ignore = false)
    {
        $inputs = $request->all();

        $rules = [
            'title.*' => 'string|required|max:255',
            'discount' => 'required|int|max:100|min:1',
        ];

        $result['validator'] = Validator::make($inputs, $rules, [
            'discount.max' => 'Процент скидки от 1 до 100',
            'discount.min' => 'Процент скидки от 1 до 100',
            'discount.int' => 'Процент скидки от 1 до 100',
            'discount.required' => 'Введите скидкиу',
            'title.*.required' => 'Введите название',
            'title.*.max' => 'Введите название',
            'title.*.string' => 'Введите название',
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }
}
