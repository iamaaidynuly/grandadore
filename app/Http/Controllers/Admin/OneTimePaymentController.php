<?php

namespace App\Http\Controllers\Admin;

use App\Models\OneTimePayment;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OneTimePaymentController extends BaseController
{
    public function main()
    {

        $data = ['title' => 'Одноразовые услуги'];

        $data['items'] = OneTimePayment::adminList();

        return view('admin.pages.OneTimePayment.main', $data);
    }

    public function add()
    {
        return redirect()->back();
        $data = [];
        $data['title'] = 'Добавление одноразовой услуги';
        $data['back_url'] = route('admin.one-time-payment.main');
        $data['edit'] = false;

        return view('admin.pages.OneTimePayment.form', $data);
    }

    public function add_put(Request $request)
    {
        return redirect()->back();

        $validator = $this->validator($request);
        $validator['validator']->validate();
        if (OneTimePayment::action(null, $validator['inputs'])) {
            Notify::success('Одноразовая услуга успешно добавлена.');

            return redirect()->route('admin.one-time-payment.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data = [];
        $data['item'] = OneTimePayment::where('id', $id)->firstOrFail();
        $data['title'] = 'Редактирование одноразовой услуги';
        $data['back_url'] = route('admin.one-time-payment.main');
        $data['edit'] = true;

        return view('admin.pages.OneTimePayment.form', $data);
    }

    public function edit_patch($id, Request $request)
    {
        $item = OneTimePayment::where('id', $id)->firstOrFail();
        $validator = $this->validator($request, $id);
        $validator['validator']->validate();
        if (OneTimePayment::action($item, $validator['inputs'])) {
            Notify::success('Одноразовая услуга успешно редактирована.');

            return redirect()->route('admin.one-time-payment.edit', ['id' => $item->id]);
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
            $item = OneTimePayment::where('id', $id)->first();
            if ($item && OneTimePayment::deleteItem($item)) $result['success'] = true;
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
            'price' => 'required|int|min:0',


        ];

        $result['validator'] = Validator::make($inputs, $rules, [
            'price.required' => 'Введите цену для пакета',
            'title.required' => 'Введите название',
            'title.string' => 'Введите название',
            'title.max' => 'Введите название (мах:255см)',
        ]);
        $result['inputs'] = $inputs;

        return $result;
    }
}
