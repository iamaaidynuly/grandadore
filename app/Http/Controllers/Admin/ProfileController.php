<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\Notify\Facades\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends BaseController
{
    public function main()
    {
        $data = ['title' => 'Настройки профиля'];
        $data['user'] = Auth::user();

        return view('admin.pages.profile.form', $data);
    }

    public function patch(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::user();
        $this->validator($inputs, $user->id, $user->password)->validate();
        if (User::action($user, $inputs)) {
            Notify::success('Профиль редактирован.');

            return redirect()->route('admin.profile.main');
        } else {
            Notify::get('error_occurred');

            return redirect()->back()->withInput();
        }
    }

    private function validator($inputs, $ignore, $current_password)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $ignore,
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($current_password) {
                    if (!Hash::check($value, $current_password)) $fail('Неправильный текуший пароль');
                }
            ],
        ];
        $messages = [
            'name.required' => 'Введите имю',
            'name.string' => 'Введите имю',
            'email.required' => 'Введите эл.почту',
            'email.email' => 'Недейтвительная эл.почта',
            'email.unique' => 'Эл.почта уже используется',
            'current_password.required' => 'Введите текуший пароль',
            'current_password.string' => 'Введите текуший пароль',

        ];
        if (!empty($inputs['change_password'])) {
            $rules['new_password'] = 'required|string|min:5|confirmed';
            $messages['new_password.required'] = 'Введите новый пароль';
            $messages['new_password.string'] = 'Введите новый пароль';
            $messages['new_password.min'] = 'Новый пароль должен содержать мин. :min символов';
            $messages['new_password.confirmed'] = 'Новый пароль и подверждение не совпадают';
        }

        return Validator::make($inputs, $rules, $messages);
    }
}
