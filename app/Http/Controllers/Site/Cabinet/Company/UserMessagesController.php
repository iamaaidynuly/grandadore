<?php

namespace App\Http\Controllers\Site\Cabinet\Company;

use App\Http\Controllers\Site\BaseController;
use App\Models\UserMessage;
use Illuminate\Http\Request;

class UserMessagesController extends BaseController
{

    public function add(Request $request)
    {
        $request->validate([
            'message' => 'string|required|max:500',
            'name' => 'string|required|max:255',
            'phone' => 'string|required|max:255',
            'email' => 'email|required|max:255',
        ], [
            'name.required' => 'ФИО обязательно',
            'name.max' => ' Мах ФИО 255',
            'name.string' => ' ФИО должно быть текстом',

            'phone.required' => 'Номер телефона обязательно',
            'phone.max' => ' Мах phone 255',
            'phone.string' => 'Номер телефона должно быть текстом',

            'email.required' => 'Эл. почта  обязательно',
            'email.max' => ' Мах Эл. почта  255',
            'email.string' => ' Эл. почта должно быть текстом',
            'email.email' => ' Эл. почта должно быть  действующим',

            'message.max' => ' Сообщение  мах 255',
            'message.required' => 'Сообщение обязательно',

            'message.string' => ' Сообщение должно быть текстом'
        ]);
        if (UserMessage::action(null, $request->input())) {
            return redirect()->back()->withInput(['success' => 'true']);
        }
    }

}

